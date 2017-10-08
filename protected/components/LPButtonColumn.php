<?php

/**
 * Button class for LivingPlants grid (based on role rights)
 *
 * @author wkoller
 */
class LPButtonColumn extends CButtonColumn {

    public $deleteButtonUrl = 'Yii::app()->controller->createUrl("delete",array("id"=>$data->id))';
    public $updateButtonUrl = 'Yii::app()->controller->createUrl("update",array("id"=>$data->id))';
    public $viewButtonUrl = 'Yii::app()->controller->createUrl("view",array("id"=>$data->id))';

    public function __construct($grid) {
        $this->template = '';

        if (Yii::app()->user->checkAccess('oprtn_readLivingplant')) {
            $this->template .= '{view}';
        }
        if (Yii::app()->user->checkAccess('oprtn_createLivingplant')) {
            $this->template .= '{update}';
        }
        if (Yii::app()->user->checkAccess('oprtn_deleteLivingplant')) {
            $this->template .= ' {delete}';
        }

        $this->header = Yii::t('jacq', 'Actions');

        parent::__construct($grid);
    }

}
