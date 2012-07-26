<?php

class LivingPlantController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
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
                'actions' => array('create', 'update', 'treeRecordFilePages', 'treeRecordFilePageView'),
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
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model_acquisitionDate = new AcquisitionDate;
        $model_acquisitionEvent = new AcquisitionEvent;
        $model_separation = new Separation;
        $model_livingPlant = new LivingPlant;
        $model_botanicalObject = new BotanicalObject;
        $model_accessionNumber = new AccessionNumber;

        if (isset($_POST['AcquisitionDate'], $_POST['AcquisitionEvent'], $_POST['LivingPlant'], $_POST['BotanicalObject'], $_POST['AccessionNumber'])) {
            $model_acquisitionDate->attributes = $_POST['AcquisitionDate'];
            $model_acquisitionEvent->attributes = $_POST['AcquisitionEvent'];
            $model_livingPlant->attributes = $_POST['LivingPlant'];
            $model_botanicalObject->attributes = $_POST['BotanicalObject'];
            $model_accessionNumber->attributes = $_POST['AccessionNumber'];
            $model_separation->attributes = $_POST['Separation'];

            if ($model_acquisitionDate->save()) {
                $model_acquisitionEvent->setAttribute('acquisition_date_id', $model_acquisitionDate->id);

                if ($model_acquisitionEvent->save()) {
                    $model_botanicalObject->setAttribute('acquisition_event_id', $model_acquisitionEvent->id);

                    // Check if we have a separation selected
                    if ($model_separation->save()) {
                        $model_botanicalObject->separation_id = $model_separation->id;

                        // Save the botanical object base
                        if ($model_botanicalObject->save()) {
                            $model_livingPlant->setAttribute('id', $model_botanicalObject->id);

                            if ($model_accessionNumber->save()) {
                                $model_livingPlant->accession_number_id = $model_accessionNumber->id;

                                if ($model_livingPlant->save()) {
                                    // Check if a tree record was selected and add it if necessary
                                    if (isset($_POST['TreeRecord']['tree_record_file_page_id'])) {
                                        $tree_record_file_page_id = intval($_POST['TreeRecord']['tree_record_file_page_id']);
                                        if ($tree_record_file_page_id > 0) {
                                            $model_livingPlantTreeRecordFilePage = new LivingPlantTreeRecordFilePage;
                                            $model_livingPlantTreeRecordFilePage->tree_record_file_page_id = $tree_record_file_page_id;
                                            $model_livingPlantTreeRecordFilePage->living_plant_id = $model_livingPlant->id;

                                            $model_livingPlantTreeRecordFilePage->save();
                                        }
                                    }

                                    $this->redirect(array('view', 'id' => $model_livingPlant->id));
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->render('create', array(
            'model_acquisitionDate' => $model_acquisitionDate,
            'model_acquisitionEvent' => $model_acquisitionEvent,
            'model_separation' => $model_separation,
            'model_livingPlant' => $model_livingPlant,
            'model_botanicalObject' => $model_botanicalObject,
            'model_accessionNumber' => $model_accessionNumber,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model_livingPlant = $this->loadModel($id);
        $model_botanicalObject = BotanicalObject::model()->findByPk($model_livingPlant->id);
        $model_separation = Separation::model()->findByPk($model_botanicalObject->getAttribute('separation_id'));
        $model_acquisitionEvent = AcquisitionEvent::model()->findByPk($model_botanicalObject->getAttribute('acquisition_event_id'));
        $model_acquisitionDate = AcquisitionDate::model()->findByPk($model_acquisitionEvent->getAttribute('acquisition_date_id'));
        $model_accessionNumber = AccessionNumber::model()->findByPk($model_livingPlant->getAttribute('accession_number_id'));

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AcquisitionDate'], $_POST['AcquisitionEvent'], $_POST['LivingPlant'], $_POST['BotanicalObject'], $_POST['AccessionNumber'])) {
            $model_acquisitionDate->attributes = $_POST['AcquisitionDate'];
            $model_acquisitionEvent->attributes = $_POST['AcquisitionEvent'];
            $model_livingPlant->attributes = $_POST['LivingPlant'];
            $model_botanicalObject->attributes = $_POST['BotanicalObject'];
            $model_accessionNumber->attributes = $_POST['AccessionNumber'];
            $model_separation->attributes = $_POST['Separation'];

            if ($model_acquisitionDate->save()) {
                $model_acquisitionEvent->setAttribute('acquisition_date_id', $model_acquisitionDate->id);

                if ($model_acquisitionEvent->save()) {
                    $model_botanicalObject->setAttribute('acquisition_event_id', $model_acquisitionEvent->id);

                    // Check if we have a separation selected
                    if ($model_separation->save()) {
                        $model_botanicalObject->separation_id = $model_separation->id;

                        // Save the botanical object base
                        if ($model_botanicalObject->save()) {
                            $model_livingPlant->setAttribute('id', $model_botanicalObject->id);

                            if ($model_accessionNumber->save()) {
                                $model_livingPlant->accession_number_id = $model_accessionNumber->id;

                                // Check if a tree record was selected and add it if necessary
                                if (isset($_POST['TreeRecord']['tree_record_file_page_id'])) {
                                    $tree_record_file_page_id = intval($_POST['TreeRecord']['tree_record_file_page_id']);
                                    if ($tree_record_file_page_id > 0) {
                                        $model_livingPlantTreeRecordFilePage = new LivingPlantTreeRecordFilePage;

                                        $model_livingPlantTreeRecordFilePage->tree_record_file_page_id = $tree_record_file_page_id;
                                        $model_livingPlantTreeRecordFilePage->living_plant_id = $model_livingPlant->id;

                                        $model_livingPlantTreeRecordFilePage->save();
                                    }
                                }
                                
                                // Update any existing entries for tree records
                                $LivingPlantTreeRecordFilePages = $_POST['LivingPlantTreeRecordFilePage'];
                                foreach( $LivingPlantTreeRecordFilePages as $LivingPlantTreeRecordFilePage_id => $LivingPlantTreeRecordFilePage ) {
                                    $LivingPlantTreeRecordFilePage_id = intval($LivingPlantTreeRecordFilePage_id);
                                    
                                    if( $LivingPlantTreeRecordFilePage_id > 0 ) {
                                        $model_livingPlantTreeRecordFilePage = LivingPlantTreeRecordFilePage::model()->findByPk($LivingPlantTreeRecordFilePage_id);
                                        
                                        if( $model_livingPlantTreeRecordFilePage != null ) {
                                            $model_livingPlantTreeRecordFilePage->corrections_done = isset($LivingPlantTreeRecordFilePage['corrections_done']) ? 1 : 0;
                                            $model_livingPlantTreeRecordFilePage->corrections_date= $LivingPlantTreeRecordFilePage['corrections_date'];
                                            
                                            $model_livingPlantTreeRecordFilePage->save();
                                        }
                                    }
                                }

                                if ($model_livingPlant->save()) {
                                    $this->redirect(array('update', 'id' => $model_livingPlant->id));
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->render('update', array(
            'model_acquisitionDate' => $model_acquisitionDate,
            'model_acquisitionEvent' => $model_acquisitionEvent,
            'model_separation' => $model_separation,
            'model_livingPlant' => $model_livingPlant,
            'model_botanicalObject' => $model_botanicalObject,
            'model_accessionNumber' => $model_accessionNumber,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('LivingPlant');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new LivingPlant('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['LivingPlant']))
            $model->attributes = $_GET['LivingPlant'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Update drop down list for available pages 
     */
    public function actionTreeRecordFilePages() {
        $data = TreeRecordFilePage::model()->findAll('tree_record_file_id=:tree_record_file_id', array(':tree_record_file_id' => intval($_POST['TreeRecord']['tree_record_file_id'])));

        $data = CHtml::listData($data, 'id', 'page');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    /**
     * Download & display a tree record file page 
     */
    public function actionTreeRecordFilePageView() {
        $tree_record_file_page_id = intval($_GET['tree_record_file_page_id']);

        $model_treeRecordFilePage = TreeRecordFilePage::model()->findByPk($tree_record_file_page_id);
        if ($model_treeRecordFilePage != null) {
            $model_treeRecordFile = TreeRecordFile::model()->findByPk($model_treeRecordFilePage->tree_record_file_id);

            if ($model_treeRecordFile != null) {
                $filePath = Yii::app()->basePath . '/uploads/treeRecords/' . $model_treeRecordFile->id . '/' . $model_treeRecordFilePage->page . '.pdf';

                // Send correct HTTP headers
                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename="' . $model_treeRecordFile->name . '"');
                header('Content-Length: ' . filesize($filePath));

                readfile($filePath);

                exit(0);
            }
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = LivingPlant::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'living-plant-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
