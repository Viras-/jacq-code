<?php

class InventoryController extends JacqController {

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
                'actions' => array('create', 'update', 'ajaxInventoryType'),
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
        $model_inventory = new Inventory;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Inventory'])) {
            $model_inventory->attributes = $_POST['Inventory'];
            if ($model_inventory->save()) {
                $this->redirect(array('view', 'id' => $model_inventory->inventory_id));
            }
        }

        $this->render('create', array(
            'model_inventory' => $model_inventory,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model_inventory = new Inventory('search');
        $model_inventory->unsetAttributes();  // clear any default values
        if (isset($_GET['Inventory'])) {
            $model_inventory->attributes = $_GET['Inventory'];
        }

        $this->render('admin', array(
            'model_inventory' => $model_inventory,
        ));
    }

    /**
     * Update form for inventory type
     * @param type $inventory_type_id
     */
    public function actionAjaxInventoryType($inventory_type_id) {
        $inventory_type_id = intval($inventory_type_id);
        if ($inventory_type_id <= 0) {
            return;
        }

        $model_inventoryType = InventoryType::model()->findByPk($inventory_type_id);

        $this->renderPartial('forms/' . $model_inventoryType->type, array(
            'model_inventoryType' => $model_inventoryType
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Inventory the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model_inventory = Inventory::model()->findByPk($id);
        if ($model_inventory === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model_inventory;
    }

    /**
     * Performs the AJAX validation.
     * @param Inventory $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'inventory-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
