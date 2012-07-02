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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AcquisitionDate'], $_POST['AcquisitionEvent'], $_POST['LivingPlant'], $_POST['BotanicalObject'])) {
            $model_acquisitionDate->attributes = $_POST['AcquisitionDate'];
            $model_acquisitionEvent->attributes = $_POST['AcquisitionEvent'];
            $model_livingPlant->attributes = $_POST['LivingPlant'];
            $model_botanicalObject->attributes = $_POST['BotanicalObject'];

            if ($model_acquisitionDate->save()) {
                $model_acquisitionEvent->setAttribute('acquisition_date_id', $model_acquisitionDate->id);

                if ($model_acquisitionEvent->save()) {
                    $model_botanicalObject->setAttribute('acquisition_event_id', $model_acquisitionEvent->id);

                    if ($model_botanicalObject->save()) {
                        $model_livingPlant->setAttribute('id', $model_botanicalObject->id);

                        if ($model_livingPlant->save()) {
                            $this->redirect(array('view', 'id' => $model_livingPlant->id));
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
            'model_botanicalObject' => $model_botanicalObject
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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['LivingPlant'])) {
            $model->attributes = $_POST['LivingPlant'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model_acquisitionDate' => $model_acquisitionDate,
            'model_acquisitionEvent' => $model_acquisitionEvent,
            'model_separation' => $model_separation,
            'model_livingPlant' => $model_livingPlant,
            'model_botanicalObject' => $model_botanicalObject
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
