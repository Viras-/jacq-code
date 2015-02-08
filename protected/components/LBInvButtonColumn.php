<?php

/**
 * Buttons for inventory list in living plant display
 *
 * @author wkoller
 */
class LBInvButtonColumn extends CButtonColumn {
    public $template = '{view}';
    
    public $viewButtonUrl = 'Yii::app()->controller->createUrl("inventory/view",array("id"=>$data->inventory_id))';
}
