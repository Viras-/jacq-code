<?php

class IndexSeminumController extends JacqController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $defaultAction = "admin";

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('admin', 'create', 'download', 'clear'),
                'roles' => array('oprtn_indexSeminum'),
            ),
            array('deny', // deny all users by default
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model_indexSeminumRevision = new IndexSeminumRevision();

        if (isset($_POST['IndexSeminumRevision'])) {
            // start a transaction for the import
            $dbTransaction = $model_indexSeminumRevision->getDbConnection()->beginTransaction();

            // start creating the snapshot
            try {
                // assign base properties
                $model_indexSeminumRevision->name = $_POST['IndexSeminumRevision']['name'];
                $model_indexSeminumRevision->user_id = Yii::app()->user->id;

                // save the revision entry and start gathering the content
                if ($model_indexSeminumRevision->save()) {
                    // fetch all records marked for the index seminum
                    $dbCriteria = new CDbCriteria();
                    $dbCriteria->compare('index_seminum', true);
                    $models_livingPlant = LivingPlant::model()->findAll($dbCriteria);

                    // iterate over model and add it to the index seminum content table
                    foreach ($models_livingPlant as $model_livingPlant) {
                        // transfer all properties to a static field in the index seminum content table
                        $model_indexSeminumContent = new IndexSeminumContent();
                        $model_indexSeminumContent->index_seminum_revision_id = $model_indexSeminumRevision->index_seminum_revision_id;
                        $model_indexSeminumContent->botanical_object_id = $model_livingPlant->id;
                        $model_indexSeminumContent->accession_number = $model_livingPlant->accessionNumber;
                        $model_indexSeminumContent->family = $model_livingPlant->id0->getFamily();
                        $model_indexSeminumContent->scientific_name = $model_livingPlant->id0->getScientificName();
                        $model_indexSeminumContent->index_seminum_type = $model_livingPlant->indexSeminumType->type;
                        $model_indexSeminumContent->ipen_number = $model_livingPlant->ipenNumber;
                        $model_indexSeminumContent->habitat = $model_livingPlant->id0->habitat;

                        // check if we have acquistion information
                        if ($model_livingPlant->id0->acquisitionEvent) {
                            $model_indexSeminumContent->acquisition_number = $model_livingPlant->id0->acquisitionEvent->number;

                            // check for coordinates / altitude information
                            if ($model_livingPlant->id0->acquisitionEvent->locationCoordinates) {
                                $model_indexSeminumContent->altitude_min = $model_livingPlant->id0->acquisitionEvent->locationCoordinates->altitude_min;
                                $model_indexSeminumContent->altitude_max = $model_livingPlant->id0->acquisitionEvent->locationCoordinates->altitude_max;
                                $model_indexSeminumContent->latitude = $model_livingPlant->id0->acquisitionEvent->locationCoordinates->getLatitudeSexagesimal();
                                $model_indexSeminumContent->longitude = $model_livingPlant->id0->acquisitionEvent->locationCoordinates->getLongitudeSexagesimal();
                            }

                            // check for date information
                            if ($model_livingPlant->id0->acquisitionEvent->acquisitionDate) {
                                $model_indexSeminumContent->acquisition_date = $model_livingPlant->id0->acquisitionEvent->acquisitionDate->getDate();
                            }
                            // check for locality information
                            if ($model_livingPlant->id0->acquisitionEvent->location) {
                                $model_indexSeminumContent->acquisition_location = $model_livingPlant->id0->acquisitionEvent->location->location;
                            }
                        }

                        // try to save the content, throw exception on error
                        if (!$model_indexSeminumContent->save()) {
                            throw new ModelException("Unable to save index seminum content", $model_indexSeminumContent);
                        }

                        // remember collector information
                        if ($model_livingPlant->id0->acquisitionEvent) {
                            foreach ($model_livingPlant->id0->acquisitionEvent->tblPeople as $model_person) {
                                $model_indexSeminumPerson = new IndexSeminumPerson();
                                $model_indexSeminumPerson->index_seminum_content_id = $model_indexSeminumContent->index_seminum_content_id;
                                $model_indexSeminumPerson->name = $model_person->name;

                                // try to save the collectors information
                                if (!$model_indexSeminumPerson->save()) {
                                    throw new ModelException("Unable to save IndexSeminumPerson", $model_indexSeminumPerson);
                                }
                            }
                        }
                    }
                }
                else {
                    throw new ModelException("Unable to save index seminum revision", $model_indexSeminumRevision);
                }

                // finally commit everything
                $dbTransaction->commit();

                // redirect to admin interface
                $this->redirect(array('admin'));
            } catch (Exception $e) {
                // if there is an error, rollback and output error message
                $dbTransaction->rollback();
                // add the exception error to the model
                $model_indexSeminumRevision->addError("name", var_export($e->getMessage(), true));
            }
        }

        $this->render('create', array(
            'model' => $model_indexSeminumRevision,
        ));
    }

    /**
     * List of index seminum revisions created before
     */
    public function actionAdmin($cleared = false) {
        $model = new IndexSeminumRevision('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['IndexSeminumRevision'])) {
            $model->attributes = $_GET['IndexSeminumRevision'];
        }

        $this->render('admin', array(
            'model' => $model,
            'cleared' => $cleared,
        ));
    }

    /**
     * Download a given index seminum revision
     * @param int $id ID of index seminum revision to load
     */
    public function actionDownload($id) {
        $id = intval($id);

        // check for a valid id
        if ($id <= 0) {
            throw new CHttpException(400, "Invalid id passed");
        }

        // try to load the index seminum revision
        $model_indexSeminumRevision = IndexSeminumRevision::model()->findByPk($id);
        if ($model_indexSeminumRevision == NULL) {
            throw new CHttpException(404, "Unable to find revision");
        }

        // require phpexcel for CSV / Excel download
        Yii::import('ext.phpexcel.XPHPExcel');

        // create phpexcel object for downloading
        $pHPExcel = XPHPExcel::createPHPExcel();

        // add header information, based on all index seminum revision attributes
        $model_indexSeminumContent = IndexSeminumContent::model();
        $metadata_indexSeminumContent = $model_indexSeminumContent->getMetaData();
        $index = 0;
        foreach ($metadata_indexSeminumContent->columns as $column_indexSeminumContent) {
            $pHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 1, $model_indexSeminumContent->getAttributeLabel($column_indexSeminumContent->name));
            $index++;
        }

        foreach ($model_indexSeminumRevision->indexSeminumContents as $row => $model_indexSeminumContent) {
            $index = 0;
            foreach ($metadata_indexSeminumContent->columns as $column_indexSeminumContent) {
                $pHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, $row + 2, $model_indexSeminumContent->getAttribute($column_indexSeminumContent->name));
                $index++;
            }

            // now add collectors to download, as they are dynamic we have to set the header each time as well
            foreach ($model_indexSeminumContent->indexSeminumPeople as $model_indexSeminumPerson) {
                $pHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 1, $model_indexSeminumPerson->getAttributeLabel('name'));
                $pHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, $row + 2, $model_indexSeminumPerson->name);
                $index++;
            }
        }

        // prepare excel sheet for download
        $pHPExcelWriter = PHPExcel_IOFactory::createWriter($pHPExcel, 'CSV');
        // configure to be excel compatible
        $pHPExcelWriter->setUseBOM(TRUE);
        $pHPExcelWriter->setDelimiter(';');

        // send header information
        header('Content-type: text/csv');
        header('Content-disposition: attachment;filename=index-seminum_' . urlencode($model_indexSeminumRevision->name) . '.csv');

        // provide output
        $pHPExcelWriter->save('php://output');
        exit(0);
    }

    /**
     * Action for cleaing all index seminum associations
     */
    public function actionClear() {
        // remove all entries from index seminum
        LivingPlant::model()->updateAll(array(
            'index_seminum' => 0
        ));

        // Redirect to default action, indicating success
        $this->redirect(array(
            $this->defaultAction,
            'cleared' => true
        ));
    }
}
