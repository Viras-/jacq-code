<?php

/**
 * @author wkoller
 */
class InventoryInventoryHandler extends InventoryHandler {

    public static $TYPE_ID = 1;

    protected function getTypeId() {
        return InventoryInventoryHandler::$TYPE_ID;
    }

    /**
     * @see InventoryHandler::renderMessage($model_inventoryObject)
     */
    protected function renderMessage($model_inventoryObject) {
        if ($model_inventoryObject->botanical_object_id) {
            return Yii::t(
                            'jacq', 'Assigned botanical object "{botanical_object_id}" to organisation "{organisation_id}".', array(
                        '{botanical_object_id}' => $model_inventoryObject->botanical_object_id,
                        '{organisation_id}' => $model_inventoryObject->message
                            )
            );
        }
        else {
            return Yii::t(
                            'jacq', 'Unable to find living plant with accession number "{accession_number}"', array(
                        '{accession_number}' => $model_inventoryObject->message
                            )
            );
        }
    }

    /**
     * Handle the actual inventory run
     * @param Inventory $model_inventory
     * @throws Exception
     */
    protected function doHandle($model_inventory) {
        if (isset($_POST['InventoryInventory'])) {
            $inventoryInventory = $_POST['InventoryInventory'];

            // extract organisation id
            $organisation_id = intval($inventoryInventory['organisation_id']);
            if ($organisation_id <= 0) {
                throw new Exception("Invalid organisation id passed");
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
                if (empty($accession_number)) {
                    continue;
                }

                // try to find living plant with this accession number
                $model_livingPlant = LivingPlant::model()->findByAttributes(array(
                    'accession_number' => $accession_number
                ));

                // check if we found an entry
                if ($model_livingPlant == NULL) {
                    // add log entry about missing accession number
                    $model_inventoryObject = new InventoryObject();
                    $model_inventoryObject->inventory_id = $model_inventory->inventory_id;
                    $model_inventoryObject->message = $accession_number;
                    if (!$model_inventoryObject->save()) {
                        throw new Exception("Unable to save log message");
                    }

                    continue;
                }

                // now update entry
                $model_livingPlant->id0->organisation_id = $organisation_id;
                if (!$model_livingPlant->id0->save()) {
                    throw new Exception("Unable to process living plant '" . $model_livingPlant->id0->id . "'");
                }

                // create log entry
                $model_inventoryObject = new InventoryObject();
                $model_inventoryObject->inventory_id = $model_inventory->inventory_id;
                $model_inventoryObject->botanical_object_id = $model_livingPlant->id0->id;
                $model_inventoryObject->message = $organisation_id;
                if (!$model_inventoryObject->save()) {
                    throw new Exception("Unable to create log entry for '" . $model_livingPlant->id0->id . "'");
                }
            }
        }
    }

}
