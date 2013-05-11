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
                'actions' => array('create', 'update', 'treeRecordFilePages', 'treeRecordFilePageView', 'ajaxCertificate', 'ajaxAcquisitionPerson', 'ajaxAlternativeAccessionNumber'),
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
        $model_locationCoordinates = new LocationCoordinates;
        $model_incomingDate = new AcquisitionDate;
        
        if (isset($_POST['AcquisitionDate'], $_POST['AcquisitionEvent'], $_POST['LivingPlant'], $_POST['BotanicalObject'], $_POST['LocationCoordinates'])) {
            $model_acquisitionDate->setDate($_POST['AcquisitionDate']['date']);
            $model_acquisitionEvent->attributes = $_POST['AcquisitionEvent'];
            $model_livingPlant->attributes = $_POST['LivingPlant'];
            $model_botanicalObject->attributes = $_POST['BotanicalObject'];
            $model_locationCoordinates->attributes = $_POST['LocationCoordinates'];
            
            if ($model_acquisitionDate->save()) {
                $model_acquisitionEvent->acquisition_date_id = $model_acquisitionDate->id;
                $locationName = trim($_POST['locationName']);

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
                    $model_botanicalObject->acquisition_event_id = $model_acquisitionEvent->id;

                    // check for acquisition persons
                    if( isset($_POST['Person']) ) {
                        foreach($_POST['Person'] as $i => $person) {
                            // remove fake id
                            unset($person['id']);
                            
                            // check for "deleted" entry
                            if( $person['delete'] > 0 ) continue;
                            
                            // try to find fitting person entry
                            $model_person = Person::getByName($person['name']);
                            if( $model_person == NULL ) {
                                $model_person = new Person();
                                $model_person->attributes = $person;
                                $model_person->save();
                            }
                            
                            // now add a connection to the person
                            $model_acquisitionEventPerson = new AcquisitionEventPerson();
                            $model_acquisitionEventPerson->acquisition_event_id = $model_acquisitionEvent->id;
                            $model_acquisitionEventPerson->person_id = $model_person->id;
                            $model_acquisitionEventPerson->save();
                        }
                    }

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
                        
                        // save the incoming date
                        $model_incomingDate->setDate($_POST['IncomingDate']['date']);
                        if( $model_incomingDate->save() ) {
                            $model_livingPlant->incoming_date_id = $model_incomingDate->id;

                            // now save living plant
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

                                // Check for alternative acqisition number entries
                                if( isset($_POST['AlternativeAccessionNumber']) ) {
                                    foreach($_POST['AlternativeAccessionNumber'] as $i => $alternativeAccessionNumber) {
                                        // auto-generate id
                                        unset($alternativeAccessionNumber['id']);

                                        // check for "deleted" entry
                                        if( $alternativeAccessionNumber['delete'] > 0 ) continue;

                                        // create new model and save it
                                        $model_alternativeAccessionNumber = new AlternativeAccessionNumber();
                                        $model_alternativeAccessionNumber->attributes = $alternativeAccessionNumber;
                                        $model_alternativeAccessionNumber->living_plant_id = $model_livingPlant->id;
                                        $model_alternativeAccessionNumber->save();
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
            'model_locationCoordinates' => $model_locationCoordinates,
            'model_incomingDate' => $model_incomingDate,
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
        $model_locationCoordinates = $model_acquisitionEvent->locationCoordinates;
        $model_incomingDate = $model_livingPlant->incomingDate;
        
        // Check if we have a correct submission
        if (isset($_POST['AcquisitionDate'], $_POST['AcquisitionEvent'], $_POST['LivingPlant'], $_POST['BotanicalObject'])) {
            $model_acquisitionDate->setDate($_POST['AcquisitionDate']['date']);
            $model_acquisitionEvent->attributes = $_POST['AcquisitionEvent'];
            $model_livingPlant->attributes = $_POST['LivingPlant'];
            $model_botanicalObject->attributes = $_POST['BotanicalObject'];
            $model_locationCoordinates->attributes = $_POST['LocationCoordinates'];
            
            if ($model_acquisitionDate->save()) {
                $model_acquisitionEvent->acquisition_date_id = $model_acquisitionDate->id;
                $locationName = trim($_POST['locationName']);

                // Check if a new (unknown) location was entered
                if ($model_acquisitionEvent->location_id <= 0 && strlen($locationName) > 0) {
                    $model_location = Location::getByName($locationName);
                    $model_acquisitionEvent->location_id = $model_location->id;
                }
                
                // save coordinates info
                $model_locationCoordinates->save();
                $model_acquisitionEvent->location_coordinates_id = $model_locationCoordinates->id;

                // check for acquisition persons
                if( isset($_POST['Person']) ) {
                    foreach($_POST['Person'] as $i => $person) {
                        // make clean id
                        $person['id'] = intval($person['id']);

                        // check for "deleted" entry
                        if( $person['delete'] > 0 ) {
                            if( $person['id'] > 0 ) {
                                // remove all connection entries
                                AcquisitionEventPerson::model()->deleteAllByAttributes(array(
                                    'acquisition_event_id' => $model_acquisitionEvent->id,
                                    'person_id' => $person['id']
                                ));
                            }
                            continue;
                        }
                        
                        // check if id is valid or if this is a new entry
                        $model_acquisitionEventPerson = AcquisitionEventPerson::model()->findByAttributes(array(
                            'acquisition_event_id' => $model_acquisitionEvent->id,
                            'person_id' => $person['id']
                        ));
                        // create new empty entry
                        if( $model_acquisitionEventPerson == NULL ) {
                            $model_acquisitionEventPerson = new AcquisitionEventPerson();
                        }

                        // try to find fitting person entry
                        $model_person = Person::getByName($person['name']);
                        if( $model_person == NULL ) {
                            $model_person = new Person();
                            $model_person->attributes = $person;
                            $model_person->save();
                        }

                        // now add a connection to the person
                        $model_acquisitionEventPerson->acquisition_event_id = $model_acquisitionEvent->id;
                        $model_acquisitionEventPerson->person_id = $model_person->id;
                        $model_acquisitionEventPerson->save();
                    }
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
                                    $separation_model = new Separation();
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
                                    }
                                    continue;
                                }
                                
                                // check if this is an existing entry
                                if( $certificate['id'] > 0 ) {
                                    $model_certificate = Certificate::model()->findByPk($certificate['id']);
                                }
                                else {
                                    $model_certificate = new Certificate();
                                }

                                // assign attributes and save it
                                $model_certificate->attributes = $certificate;
                                $model_certificate->living_plant_id = $model_livingPlant->id;
                                $model_certificate->save();
                            }
                        }

                        // Check for alternative acqisition number entries
                        if( isset($_POST['AlternativeAccessionNumber']) ) {
                            foreach($_POST['AlternativeAccessionNumber'] as $i => $alternativeAccessionNumber) {
                                // make sure we have a clean integer as id
                                $alternativeAccessionNumber['id'] = intval($alternativeAccessionNumber['id']);

                                // check for "deleted" entry
                                if( $alternativeAccessionNumber['delete'] > 0 ) {
                                    if($alternativeAccessionNumber['id'] > 0) {
                                        AlternativeAccessionNumber::model()->deleteByPk($alternativeAccessionNumber['id']);
                                    }
                                    continue;
                                }
                                
                                // check if this is an existing entry
                                if( $alternativeAccessionNumber['id'] > 0 ) {
                                    $model_alternativeAccessionNumber = AlternativeAccessionNumber::model()->findByPk($alternativeAccessionNumber['id']);
                                }
                                else {
                                    $model_alternativeAccessionNumber = new AlternativeAccessionNumber();
                                }

                                // assign attributes and save it
                                $model_alternativeAccessionNumber->attributes = $alternativeAccessionNumber;
                                $model_alternativeAccessionNumber->living_plant_id = $model_livingPlant->id;
                                $model_alternativeAccessionNumber->save();
                            }
                        }
                        
                        // update incoming date and save it
                        $model_incomingDate->setDate($_POST['IncomingDate']['date']);
                        if( $model_incomingDate->save() ) {
                            // finally save the living plant
                            if ($model_livingPlant->save()) {
                                $this->redirect(array('update', 'id' => $model_livingPlant->id));
                            }
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
            'model_locationCoordinates' => $model_locationCoordinates,
            'model_incomingDate' => $model_incomingDate,
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
     * renders form for entering a new certificate 
     */
    public function actionAjaxAcquisitionPerson() {
        $model_acquisitionPerson = new Person();
        
        $this->renderPartial('form_acquisitionPerson', array(
            'model_acquisitionPerson' => $model_acquisitionPerson
        ), false, true);
    }
    
    /**
     * renders form for entering a new certificate 
     */
    public function actionAjaxAlternativeAccessionNumber() {
        $model_alternativeAccessionNumber = new AlternativeAccessionNumber();
        
        $this->renderPartial('form_alternativeAccessionNumber', array(
            'model_alternativeAccessionNumber' => $model_alternativeAccessionNumber
        ), false, true);
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
        $accessionAccess = Yii::app()->authorization->botanicalObjectAccess($model->id, Yii::app()->user->getId());
        if( $accessionAccess != NULL ) $bAllowAccess = $accessionAccess;
        
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
        
        // check for explicit allowal or denial
        if( $model_accessOrganisation->allowDeny == 1 ) {
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
