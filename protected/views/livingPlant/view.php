<?php
$this->breadcrumbs=array(
	'Living Plants'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List LivingPlant', 'url'=>array('index')),
	array('label'=>'Create LivingPlant', 'url'=>array('create')),
	array('label'=>'Update LivingPlant', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete LivingPlant', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LivingPlant', 'url'=>array('admin')),
);
?>

<h1>View LivingPlant #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'garden_site_id',
		'accession_number_id',
		'ipen_number',
		'phyto_control',
		'tree_record_id',
		'phyto_sanitary_product_number',
	),
));
