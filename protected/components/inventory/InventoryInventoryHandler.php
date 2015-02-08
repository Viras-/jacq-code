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
        if( $model_inventoryObject->botanical_object_id ) {
            return Yii::t(
                    'jacq',
                    'Assigned botanical object "{botanical_object_id}" to organisation "{organisation_id}".',
                    array(
                        '{botanical_object_id}' => $model_inventoryObject->botanical_object_id,
                        '{organisation_id}' => $model_inventoryObject->message
                    )
            );
        }
        else {
            return Yii::t(
                    'jacq', 
                    'Unable to find living plant with accession number "{accession_number}"',
                    array(
                        '{accession_number}' => $model_inventoryObject->message
                    )
            );
        }
    }
}
