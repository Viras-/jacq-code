<?php
$this->breadcrumbs = array(
    Yii::t('jacq', 'Living Plants') => array('index'),
    Yii::t('jacq', 'Manage'),
);

$this->menu = array(
//    array('label' => 'List LivingPlant', 'url' => array('index')),
    array('label' => Yii::t('jacq', 'Create Living Plant'), 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('living-plant-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('jacq', 'Manage Living Plants'); ?></h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'living-plant-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'filterSelector' => '{filter}',
    'columns' => array(
        array('name' => 'scientificName_search', 'value' => '$data->id0->scientificName'),
        array('name' => 'organisation_search', 'value' => '$data->id0->organisation->description'),
        array('name' => 'accession_number', 'value' => '$data->accessionNumber'),
        array('name' => 'location_search', 'value' => '(isset($data->id0->acquisitionEvent->location->location)) ? $data->id0->acquisitionEvent->location->location : ""'),
        array(
            'class' => 'LPButtonColumn',
        ),
    ),
));
?>
