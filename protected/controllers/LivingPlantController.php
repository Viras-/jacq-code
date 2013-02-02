<?php

class LivingPlantController extends Controller {
    /**
     * Return connection to herbarinput database
     * @return CDbConnection
     */
    private function getDbHerbarInput() {
        return Yii::app()->dbHerbarInput;
    }

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
            array('allow', // reading
                'actions' => array('admin', 'view', 'index'),
                'roles' => array('oprtn_readLivingplant'),
            ),
            array('allow', // creating / updating
                'actions' => array('create', 'update', 'treeRecordFilePages', 'treeRecordFilePageView', 'ajaxCertificate', 'ajaxCertificateDelete', 'ajaxCertificateStore'),
                'roles' => array('oprtn_createLivingplant'),
            ),
            array('allow', // deleting
                'actions' => array('delete'),
                'roles' => array('oprtn_deleteLivingplant'),
            ),
            array('deny', // deny all users by default
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
            'model_livingPlant' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model_acquisitionDate = new AcquisitionDate;
        $model_acquisitionEvent = new AcquisitionEvent;
        $model_livingPlant = new LivingPlant;
        $model_botanicalObject = new BotanicalObject;
        $model_accessionNumber = new AccessionNumber;
        $model_locationCoordinates = new LocationCoordinates;
        
        $model_livingPlant->id0 = $model_botanicalObject;
        
        if (isset($_POST['AcquisitionDate'], $_POST['AcquisitionEvent'], $_POST['LivingPlant'], $_POST['BotanicalObject'], $_POST['AccessionNumber'])) {
            $model_acquisitionDate->attributes = $_POST['AcquisitionDate'];
            $model_acquisitionEvent->attributes = $_POST['AcquisitionEvent'];
            $model_livingPlant->attributes = $_POST['LivingPlant'];
            $model_botanicalObject->attributes = $_POST['BotanicalObject'];
            $model_accessionNumber->attributes = $_POST['AccessionNumber'];
            $model_locationCoordinates->attributes = $_POST['LocationCoordinates'];
            
            if ($model_acquisitionDate->save()) {
                $model_acquisitionEvent->acquisition_date_id = $model_acquisitionDate->id;
                $locationName = trim($_POST['locationName']);
                $personName = trim($_POST['AcqusitionEvent_personName']);

                // Check if a new (unknown) location was entered
                if ($model_acquisitionEvent->location_id <= 0 && strlen($locationName) > 0) {
                    $model_location = Location::getByName($locationName);
                    $model_acquisitionEvent->location_id = $model_location->id;
                }

                // save coordinates info
                $model_locationCoordinates->save();
                $model_acquisitionEvent->location_coordinates_id = $model_locationCoordinates->id;

                // Save acquisition-event and procede
                if ($model_acquisitionEvent->save()) {
                    // Check if a new (unknown) agent was entered and add it to acquisition event
                    if (strlen($personName) > 0) {
                        $model_person = Person::getByName($personName);
                        $model_acquisitionEventPerson = new AcquisitionEventPerson;
                        $model_acquisitionEventPerson->acquisition_event_id = $model_acquisitionEvent->id;
                        $model_acquisitionEventPerson->person_id = $model_person->id;
                        $model_acquisitionEventPerson->save();
                    }

                    $model_botanicalObject->acquisition_event_id = $model_acquisitionEvent->id;

                    // Check if we have a separation selected
                    $determinedByName = trim($_POST['determinedByName']);

                    // Check if a new (unknown) determined by was entered
                    if ($model_botanicalObject->determined_by_id <= 0 && strlen($determinedByName) > 0) {
                        $model_determinedBy = Person::getByName($determinedByName);
                        $model_botanicalObject->determined_by_id = $model_determinedBy->id;
                    }

                    // Save the botanical object base
                    if ($model_botanicalObject->save()) {
                        $model_livingPlant->id = $model_botanicalObject->id;

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

                                // Check if a relevancy type was selected & add it
                                if (isset($_POST['RelevancyType'])) {
                                    foreach ($_POST['RelevancyType'] as $relevancy_type_id) {
                                        $model_relevancy = new Relevancy;
                                        $model_relevancy->living_plant_id = $model_livingPlant->id;
                                        $model_relevancy->relevancy_type_id = $relevancy_type_id;
                                        $model_relevancy->save();
                                    }
                                }

                                // Check if a sex was selected & add it
                                if (isset($_POST['Sex'])) {
                                    foreach ($_POST['Sex'] as $sex_id) {
                                        $model_botanicalObjectSex = new BotanicalObjectSex;
                                        $model_botanicalObjectSex->botanical_object_id = $model_livingPlant->id;
                                        $model_botanicalObjectSex->sex_id = $sex_id;
                                        $model_botanicalObjectSex->save();
                                    }
                                }

                                // check for separation entries and update/add them
                                if( isset($_POST['Separation']) ) {
                                    // cycle through all posted separation entries
                                    foreach($_POST['Separation'] as $i => $separation) {
                                        // only handle separation entry if it has a type set
                                        if( empty($separation['separation_type_id']) ) continue;

                                        // check if this is an update and load the model
                                        $separation_model = new Separation;

                                        // set the attributes & save the separation entry
                                        $separation_model->attributes = $separation;
                                        $separation_model->botanical_object_id = $model_botanicalObject->id;
                                        $separation_model->save();
                                    }
                                }
                                
                                // Check for certificate entries
                                if( isset($_POST['Certificate']) ) {
                                    foreach($_POST['Certificate'] as $i => $certificate) {
                                        // auto-generate id
                                        unset($certificate['id']);
                                        
                                        // check for "deleted" entry
                                        if( $certificate['delete'] > 0 ) continue;
                                        
                                        // create new model and save it
                                        $model_certificate = new Certificate();
                                        $model_certificate->attributes = $certificate;
                                        $model_certificate->living_plant_id = $model_livingPlant->id;
                                        $model_certificate->save();
                                    }
                                }

                                // Redirect to update page directly
                                $this->redirect(array('update', 'id' => $model_livingPlant->id));
                            }
                        }
                    }
                }
            }
        }

        // Render the create form
        $this->render('create', array(
            'model_acquisitionDate' => $model_acquisitionDate,
            'model_acquisitionEvent' => $model_acquisitionEvent,
            'model_livingPlant' => $model_livingPlant,
            'model_botanicalObject' => $model_botanicalObject,
            'model_accessionNumber' => $model_accessionNumber,
            'model_locationCoordinates' => $model_locationCoordinates,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model_livingPlant = $this->loadModel($id);
        $model_botanicalObject = $model_livingPlant->id0;
        $model_acquisitionEvent = $model_botanicalObject->acquisitionEvent;
        $model_acquisitionDate = $model_acquisitionEvent->acquisitionDate;
        $model_accessionNumber = $model_livingPlant->accessionNumber;
        $model_locationCoordinates = $model_acquisitionEvent->locationCoordinates;
        
        // Check if we have a correct submission
        if (isset($_POST['AcquisitionDate'], $_POST['AcquisitionEvent'], $_POST['LivingPlant'], $_POST['BotanicalObject'])) {
            $model_acquisitionDate->attributes = $_POST['AcquisitionDate'];
            $model_acquisitionEvent->attributes = $_POST['AcquisitionEvent'];
            $model_livingPlant->attributes = $_POST['LivingPlant'];
            $model_botanicalObject->attributes = $_POST['BotanicalObject'];
            $model_locationCoordinates->attributes = $_POST['LocationCoordinates'];
            
            if ($model_acquisitionDate->save()) {
                $model_acquisitionEvent->acquisition_date_id = $model_acquisitionDate->id;
                $locationName = trim($_POST['locationName']);
                $personName = trim($_POST['AcqusitionEvent_personName']);

                // Check if a new (unknown) location was entered
                if ($model_acquisitionEvent->location_id <= 0 && strlen($locationName) > 0) {
                    $model_location = Location::getByName($locationName);
                    $model_acquisitionEvent->location_id = $model_location->id;
                }
                
                // save coordinates info
                $model_locationCoordinates->save();
                $model_acquisitionEvent->location_coordinates_id = $model_locationCoordinates->id;

                // Check if a new (unknown) agent was entered
                if (strlen($personName) > 0) {
                    $model_person = Person::getByName($personName);
                    $model_acquisitionEventPerson = new AcquisitionEventPerson;
                    $model_acquisitionEventPerson->acquisition_event_id = $model_acquisitionEvent->id;
                    $model_acquisitionEventPerson->person_id = $model_person->id;
                    $model_acquisitionEventPerson->save();
                }

                if ($model_acquisitionEvent->save()) {
                    $model_botanicalObject->acquisition_event_id = $model_acquisitionEvent->id;

                    // Check if we have a separation selected
                    $determinedByName = trim($_POST['determinedByName']);

                    // Check if a new (unknown) determined by was entered
                    if ($model_botanicalObject->determined_by_id <= 0 && strlen($determinedByName) > 0) {
                        $model_determinedBy = Person::getByName($determinedByName);
                        $model_botanicalObject->determined_by_id = $model_determinedBy->id;
                    }

                    // Save the botanical object base
                    if ($model_botanicalObject->save()) {
                        $model_livingPlant->id = $model_botanicalObject->id;
                        
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
                        if (isset($_POST['LivingPlantTreeRecordFilePage'])) {
                            $LivingPlantTreeRecordFilePages = $_POST['LivingPlantTreeRecordFilePage'];
                            foreach ($LivingPlantTreeRecordFilePages as $LivingPlantTreeRecordFilePage_id => $LivingPlantTreeRecordFilePage) {
                                $LivingPlantTreeRecordFilePage_id = intval($LivingPlantTreeRecordFilePage_id);

                                if ($LivingPlantTreeRecordFilePage_id > 0) {
                                    $model_livingPlantTreeRecordFilePage = LivingPlantTreeRecordFilePage::model()->findByPk($LivingPlantTreeRecordFilePage_id);

                                    if ($model_livingPlantTreeRecordFilePage != null) {
                                        $model_livingPlantTreeRecordFilePage->corrections_done = isset($LivingPlantTreeRecordFilePage['corrections_done']) ? 1 : 0;
                                        $model_livingPlantTreeRecordFilePage->corrections_date = $LivingPlantTreeRecordFilePage['corrections_date'];

                                        $model_livingPlantTreeRecordFilePage->save();
                                    }
                                }
                            }
                        }

                        // Remove all previously added relevancy types
                        Relevancy::model()->deleteAll(
                                'living_plant_id=:living_plant_id', array(
                            ':living_plant_id' => $model_livingPlant->id,
                                )
                        );
                        // Check if a relevancy type was selected & add it
                        if (isset($_POST['RelevancyType'])) {
                            foreach ($_POST['RelevancyType'] as $relevancy_type_id) {
                                $model_relevancy = new Relevancy;
                                $model_relevancy->living_plant_id = $model_livingPlant->id;
                                $model_relevancy->relevancy_type_id = $relevancy_type_id;
                                $model_relevancy->save();
                            }
                        }

                        // Remove all previously added sexes
                        BotanicalObjectSex::model()->deleteAll(
                                'botanical_object_id=:botanical_object_id', array(
                            ':botanical_object_id' => $model_livingPlant->id,
                                )
                        );
                        // Check if a sex was selected & add it
                        if (isset($_POST['Sex'])) {
                            foreach ($_POST['Sex'] as $sex_id) {
                                $model_botanicalObjectSex = new BotanicalObjectSex;
                                $model_botanicalObjectSex->botanical_object_id = $model_livingPlant->id;
                                $model_botanicalObjectSex->sex_id = $sex_id;
                                $model_botanicalObjectSex->save();
                            }
                        }

                        // check for separation entries and update/add them
                        if( isset($_POST['Separation']) ) {
                            // cycle through all posted separation entries
                            foreach($_POST['Separation'] as $i => $separation) {
                                // only handle separation entry if it has a type set
                                if( empty($separation['separation_type_id']) ) continue;

                                // check if this is an update and load the model
                                $separation_model = null;
                                if( isset($separation['id']) ) {
                                    $separation_model = Separation::model()->findByPk($separation['id']);
                                }
                                // .. else create a new separation entry
                                else {
                                    $separation_model = new Separation;
                                }

                                // set the attributes & save the separation entry
                                $separation_model->attributes = $separation;
                                $separation_model->botanical_object_id = $model_botanicalObject->id;
                                $separation_model->save();
                            }
                        }
                        
                        // Check for certificate entries
                        if( isset($_POST['Certificate']) ) {
                            foreach($_POST['Certificate'] as $i => $certificate) {
                                // make sure we have a clean integer as id
                                $certificate['id'] = intval($certificate['id']);
                                
                                // check for "deleted" entry
                                if( $certificate['delete'] > 0 ) {
                                    if($certificate['id'] > 0) {
                                        Certificate::model()->deleteByPk($certificate['id']);
                                        continue;
                                    }
                                }

                                // create new model and save it
                                $model_certificate = new Certificate();
                                $model_certificate->attributes = $certificate;
                                $model_certificate->living_plant_id = $model_livingPlant->id;
                                $model_certificate->save();
                            }
                        }

                        if ($model_livingPlant->save()) {
                            $this->redirect(array('update', 'id' => $model_livingPlant->id));
                        }
                    }
                }
            }
        }

        // Render the update form
        $this->render('update', array(
            'model_acquisitionDate' => $model_acquisitionDate,
            'model_acquisitionEvent' => $model_acquisitionEvent,
            'model_livingPlant' => $model_livingPlant,
            'model_botanicalObject' => $model_botanicalObject,
            'model_accessionNumber' => $model_accessionNumber,
            'model_locationCoordinates' => $model_locationCoordinates,
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
        $this->actionAdmin();
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
        $content_disposition = (isset($_GET['download'])) ? 'attachment' : 'inline';

        $model_treeRecordFilePage = TreeRecordFilePage::model()->findByPk($tree_record_file_page_id);
        if ($model_treeRecordFilePage != null) {
            $model_treeRecordFile = TreeRecordFile::model()->findByPk($model_treeRecordFilePage->tree_record_file_id);

            if ($model_treeRecordFile != null) {
                $filePath = Yii::app()->basePath . '/uploads/treeRecords/' . $model_treeRecordFile->id . '/' . $model_treeRecordFilePage->page . '.pdf';

                // Send correct HTTP headers
                header('Content-type: application/pdf');
                header('Content-Disposition: ' . $content_disposition . '; filename="' . $model_treeRecordFile->name . '_' . $model_treeRecordFilePage->page . '.pdf"');
                header('Content-Length: ' . filesize($filePath));

                readfile($filePath);

                exit(0);
            }
        }
    }
    
    /**
     * renders form for entering a new certificate 
     */
    public function actionAjaxCertificate() {
        $model_certificate = new Certificate;
        
        $this->renderPartial('form_certificate', array(
            'model_certificate' => $model_certificate,
        ));
    }
    
    /**
     * add / update a certificate 
     */
    public function actionAjaxCertificateStore() {
        $id = (isset($_POST['id'])) ? intval($_POST['id']) : 0;
        unset($_POST['id']);

        // load or create certificate entry
        $model_certificate = ($id > 0) ? Certificate::model()->findByPk($id) : new Certificate;
        // save updated certificate information
        $model_certificate->attributes = $_POST;
        $model_certificate->save();
        
        // echo id of model certificate
        echo $model_certificate->id;
    }
    
    /**
     * remove a certificate 
     */
    public function actionAjaxCertificateDelete($id) {
        $id = intval($id);
        
        // check for valid id
        if( $id > 0 ) {
            // find model entry & delete it
            $model_certificate = Certificate::model()->findByPk($id);
            if( $model_certificate != null ) {
                $model_certificate->delete();
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
        
        // check if user is allowed to access this model
        // default rights setup
        $bAllowAccess = false;
        if( !$model->id0->organisation->greenhouse ) {
            $bAllowAccess = true;
        }
        
        /**
         * greenhouse level
         */
        if( Yii::app()->user->checkAccess('acs_greenhouse') ) {
            $bAllowAccess = true;
        }

        /**
         * organisation level
         */
        $user_id = Yii::app()->user->getId();
        $authAssignments = Yii::app()->authManager->getAuthAssignments($user_id);
        $bNewAllowAccess = false;
        // check all groups for access
        foreach( $authAssignments as $itemName => $authAssignment ) {
            // check if this group has an assignment in the access table
            $model_accessOrganisation = AccessOrganisation::model()->findByAttributes(
                    array(
                        'AuthItem_name' => $itemName,
                        'organisation_id' => $model->id0->organisation->id
                    )
            );
            
            $bNewAllowAccess = $this->checkAccessOrganisation($model_accessOrganisation, $bAllowAccess);
            // check for explicit allowal in this group, if so break and ignore all other settings
            if( $bNewAllowAccess && !$bAllowAccess ) {
                $bAllowAccess = true;
                break;
            }
            $bAllowAccess = $bNewAllowAccess;
        }
        // now check the organisation access for this user
        $model_accessOrganisation = AccessOrganisation::model()->findByAttributes(
                array(
                    'user_id' => Yii::app()->user->getId(),
                    'organisation_id' => $model->id0->organisation->id
                )
        );
        $bAllowAccess = $this->checkAccessOrganisation($model_accessOrganisation, $bAllowAccess);
        
        /**
         * Accession (livingplant) level 
         */
        $bNewAllowAccess = false;
        // check all groups for access
        foreach( $authAssignments as $itemName => $authAssignment ) {
            // check if this group has an assignment in the access table
            $model_accessLivingplant = AccessLivingplant::model()->findByAttributes(
                    array(
                        'AuthItem_name' => $itemName,
                        'living_plant_id' => $model->id
                    )
            );
            
            $bNewAllowAccess = $this->checkAccessLivingplant($model_accessLivingplant, $bAllowAccess);
            error_log('check: ' . $itemName . ' / ' . $bNewAllowAccess . ' / ' . $bAllowAccess);
            // check for explizit allowal in this group, if so break and ignore all other settings
            if( $bNewAllowAccess && !$bAllowAccess ) {
                $bAllowAccess = true;
                break;
            }
            $bAllowAccess = $bNewAllowAccess;
        }
        // now check the access for this user
        $model_accessLivingplant = AccessLivingplant::model()->findByAttributes(
                array(
                    'user_id' => Yii::app()->user->getId(),
                    'living_plant_id' => $model->id
                )
        );
        $bAllowAccess = $this->checkAccessLivingplant($model_accessLivingplant, $bAllowAccess);
        
        // finally check the result of the access checking
        if( !$bAllowAccess ) {
            throw new CHttpException(401, 'You are not allowed to access this page.');
        }
        
        return $model;
    }
    
    /**
     * Helper function for checking the access on organisation level
     * @param AccessOrganisation $model_accessOrganisation
     * @param boolean $bAllowAccess input value for AllowAccess
     * @return null|boolean null if no access information is available, else true or false
     */
    private function checkAccessOrganisation($model_accessOrganisation, $bAllowAccess) {
        // check for valid model
        if( $model_accessOrganisation == null ) return $bAllowAccess;
        
        // check for explizit allowal or denial
        if( $model_accessOrganisation->allowDeny == 1 ) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
     * Helper function for checking the access on organisation level
     * @param AccessLivingplant $model_accessLivingplant
     * @param boolean $bAllowAccess input value for AllowAccess
     * @return null|boolean null if no access information is available, else true or false
     */
    private function checkAccessLivingplant($model_accessLivingplant, $bAllowAccess) {
        // check for valid model
        if( $model_accessLivingplant == null ) return $bAllowAccess;
        
        error_log($model_accessLivingplant->allowDeny);
        
        // check for explizit allowal or denial
        if( $model_accessLivingplant->allowDeny == 1 ) {
            return true;
        }
        else {
            return false;
        }
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
