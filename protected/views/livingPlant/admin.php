<?php
$this->breadcrumbs = array(
    Yii::t('jacq', 'Living Plants') => array('index'),
    Yii::t('jacq', 'Manage'),
);

$this->menu = array(
//    array('label' => 'List LivingPlant', 'url' => array('index')),
    array('label' => Yii::t('jacq', 'Create Living Plant'), 'url' => array('create')),
    array('label' => Yii::t('jacq', 'Download Label-Template'), 'url' => Yii::app()->baseUrl . '/downloads/WU-Freilandetikett.doc'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#living-plant-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('jacq', 'Manage Living Plants'); ?></h1>

<div class="search-form">
    <?php
    $lpGridView = $this->renderPartial('_search',array(
            'model'=>$model,
    )); ?>
</div><!-- search-form -->

<div style="text-align: right;">
<?php
// add export button for CSV
$this->renderExportGridButton(
        'living-plant-grid',
        CHtml::image('images/table_save.png', Yii::t('jacq', 'Download CSV')),
        array()
);
?>
</div>
<?php
$lpGridView = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'living-plant-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array('name' => 'scientificName_search', 'value' => '$data->id0->scientificName'),
        array('name' => 'organisation_search', 'value' => '$data->id0->organisation->description'),
        array('name' => 'accessionNumber_search', 'value' => '$data->accession_number'),
        array('name' => 'location_search', 'value' => '(isset($data->id0->acquisitionEvent->location->location)) ? $data->id0->acquisitionEvent->location->location : ""'),
        array(
            'class' => 'LPButtonColumn',
        ),
    ),
));
