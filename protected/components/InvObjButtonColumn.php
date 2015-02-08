<?php

/**
 * Buttons for Inventory object list in inventory view
 *
 * @author wkoller
 */
class InvObjButtonColumn extends CButtonColumn {
    public $template = '{view}';
    
    public $viewButtonUrl = 'Yii::app()->controller->createUrl("livingPlant/update",array("id"=>$data->botanical_object_id))';
}
