<?php
$this->breadcrumbs=array(
	'Garden Sites'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GardenSite', 'url'=>array('index')),
	array('label'=>'Create GardenSite', 'url'=>array('create')),
	array('label'=>'Update GardenSite', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GardenSite', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GardenSite', 'url'=>array('admin')),
);
?>

<h1>View GardenSite #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'description',
		'department',
		'greenhouse',
		'parent_organisation_id',
		'gardener_id',
	),
));
