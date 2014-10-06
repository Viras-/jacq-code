<?php

/**
 * Grid column for rendering the CSV download button for index seminum
 *
 * @author wkoller
 */
class ISButtonColumn extends CButtonColumn {
    public $template = '{view}';
    public $viewButtonUrl = 'Yii::app()->controller->createUrl("download",array("id"=>$data->primaryKey))';
    public $viewButtonImageUrl = 'images/disk.png';
}
