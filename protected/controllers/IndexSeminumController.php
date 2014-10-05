<?php

class IndexSeminumController extends JacqController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $defaultAction = "admin";

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
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
                        $model_indexSeminumContent->accession_number = $model_livingPlant->accession_number;
                        $model_indexSeminumContent->family = $model_livingPlant->id0->getFamily();
                        $model_indexSeminumContent->scientific_name = $model_livingPlant->id0->getScientificName();
                        $model_indexSeminumContent->index_seminum_type = $model_livingPlant->indexSeminumType->type;
                        $model_indexSeminumContent->ipen_number = $model_livingPlant->ipen_number;
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
                                $model_indexSeminumContent->acquisition_locality = $model_livingPlant->id0->acquisitionEvent->location->location;
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
                } else {
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
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new IndexSeminumRevision('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['IndexSeminumRevision']))
            $model->attributes = $_GET['IndexSeminumRevision'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

}
