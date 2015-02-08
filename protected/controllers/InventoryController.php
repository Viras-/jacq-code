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
        // prepare model for searching all related inventory object entries
        $model_inventoryObject = new InventoryObject('search');
        $model_inventoryObject->unsetAttributes();
        if (isset($_GET['InventoryObject'])) {
            $model_inventoryObject->attributes = $_GET['InventoryObject'];
        }
        // always make sure we only load related entries
        $model_inventoryObject->inventory_id = $id;

        $this->render('view', array(
            'model_inventory' => $this->loadModel($id),
            'model_inventoryObject' => $model_inventoryObject,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        // require phpexcel for CSV / Excel processing
        Yii::import('ext.phpexcel.XPHPExcel');
        // create PHPExcel object for loading PHPExcel environment
        $pHPExcel = XPHPExcel::createPHPExcel();

        // empty wrapper model
        $model_inventory = new Inventory;

        if (isset($_POST['Inventory'])) {
            $model_inventory->attributes = $_POST['Inventory'];
            
            // do inventory runs in transaction mode
            $dbTransaction = $model_inventory->getDbConnection()->beginTransaction();

            try {
                // update user entry
                $model_inventory->user_id = Yii::app()->user->id;
                
                // only if the inventory model is complete, we check the actual content
                if ($model_inventory->save()) {
                    switch ($model_inventory->inventory_type_id) {
                        // Inventory run
                        case 1:
                            $model_inventoryData = $this->inventoryInventory($model_inventory);
                            break;
                    }
                    
                    // if we reach here, everything went well and we can commit the inventory run
                    $dbTransaction->commit();
                    $this->redirect(array('admin'));
                }
                else {
                    throw new Exception("Unable to save inventory base entry");
                }
            }
            // catch any error and rollback
            catch(Exception $e) {
                $dbTransaction->rollback();
            }

        }

        $this->render('create', array(
            'model_inventory' => $model_inventory,
        ));
    }

    /**
     * Handle a "normal" inventory run
     * @param Inventory $model_inventory
     * @throws Exception
     */
    protected function inventoryInventory($model_inventory) {
        if (isset($_POST['InventoryInventory'])) {
            $inventoryInventory = $_POST['InventoryInventory'];

            // extract organisation id
            $organisation_id = intval($inventoryInventory['organisation_id']);
            if ($organisation_id <= 0) {
                return null;
            }

            // extract file upload information
            $uploadedFile = CUploadedFile::getInstanceByName('InventoryInventory[inventory_file]');

            // identify file and read it into memory
            $inputFileType = PHPExcel_IOFactory::identify($uploadedFile->getTempName());
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($uploadedFile->getTempName());

            // extract dimensions
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();

            // iterate over content
            for ($row = 1; $row <= $highestRow; $row++) {
                // treat cell entry as accession number, check for validity
                $accession_number = trim($sheet->getCellByColumnAndRow(0, $row));
                if( empty($accession_number) ) {
                    continue;
                }

                // try to find living plant with this accession number
                $model_livingPlant = LivingPlant::model()->findByAttributes(array(
                    'accession_number' => $accession_number
                ));

                // check if we found an entry
                if( $model_livingPlant == NULL ) {
                    // add log entry about missing accession number
                    $model_inventoryObject = new InventoryObject();
                    $model_inventoryObject->inventory_id = $model_inventory->inventory_id;
                    $model_inventoryObject->message = $accession_number;
                    if( $model_inventoryObject->save() ) {
                        throw new Exception("Unable to save log message");
                    }

                    continue;
                }

                // now update entry
                $model_livingPlant->id0->organisation_id = $organisation_id;
                if( !$model_livingPlant->id0->save() ) {
                    throw new Exception("Unable to process living plant '" . $model_livingPlant->id0->id . "'");
                }

                // create log entry
                $model_inventoryObject = new InventoryObject();
                $model_inventoryObject->inventory_id = $model_inventory->inventory_id;
                $model_inventoryObject->botanical_object_id = $model_livingPlant->id0->id;
                $model_inventoryObject->message = $organisation_id;
                if( !$model_inventoryObject->save() ) {
                    throw new Exception("Unable to create log entry for '" . $model_livingPlant->id0->id . "'");
                }
            }
        }
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
