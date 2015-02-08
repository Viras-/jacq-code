<?php
/**
 * @author wkoller
 */
abstract class InventoryHandler extends CComponent {
    /**
     * Automatically register each inventory-handler instance
     */
    public function init() {
        // register this instance as type handler
        InventoryHandler::$inventoryHandlers[$this->getTypeId()] = $this;
    }
    
    /**
     * Implement with your own rendering info
     * @param InventoryObject $model_inventoryObject inventory object entry to render
     */
    protected abstract function renderMessage($model_inventoryObject);

    /**
     * Return the inventory_type_id which this handler is responsible for
     */
    protected abstract function getTypeId();
    
    /**
     * Static function for rendering the human readable message for a given inventory object
     * @param InventoryObject $model_inventoryObject
     * @return string
     */
    public static function getMessage($model_inventoryObject) {
        $inventoryHandler = InventoryHandler::$inventoryHandlers[$model_inventoryObject->inventory->inventory_type_id];
        
        return $inventoryHandler->renderMessage($model_inventoryObject);
    }
    
    /**
     * Internal referencers to all registered inventory handlers
     * @var array
     */
    protected static $inventoryHandlers = array();
}
