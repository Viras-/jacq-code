<?php

class UserController extends JacqController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow user with createUser role to access management functionality
                'actions' => array('admin', 'create', 'delete', 'index', 'update'),
                'roles' => array('oprtn_createUser'),
            ),
            array('allow', // allow authenticated user to update their profile
                'actions' => array('profile'),
                'users' => array('@'),
            ),
            array('deny', // deny everything by default
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new User;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save())
                $this->redirect(array('update', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['User'])) {
            // protect the username, salt & password properties
            unset($_POST['User']['username']);
            unset($_POST['User']['salt']);
            unset($_POST['User']['password']);

            $model->attributes = $_POST['User'];
            $model->save();
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }
    
    /**
     * Provides limited editing functionality available for the owning user
     */
    public function actionProfile() {
        $model = $this->loadModel(Yii::app()->user->getId());
        $model->scenario = 'profile';
        
        if (isset($_POST['User'])) {
            // only allow specific attributes to be updated
            $model->new_password = $_POST['User']['new_password'];
            $model->new_password_confirm = $_POST['User']['new_password_confirm'];
            // clear the forced password change
            $model->force_password_change = 0;
            
            // save the updated user profile
            $model->save();
        }
        
        $this->render('profile', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Use admin action by default
     */
    public function actionIndex() {
        $this->actionAdmin();
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
