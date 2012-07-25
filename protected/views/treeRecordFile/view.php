<?php
$this->breadcrumbs=array(
	'Tree Record Files'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List TreeRecordFile', 'url'=>array('index')),
	array('label'=>'Create TreeRecordFile', 'url'=>array('create')),
	array('label'=>'Update TreeRecordFile', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TreeRecordFile', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TreeRecordFile', 'url'=>array('admin')),
);
?>

<h1>View TreeRecordFile #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'year',
		'name',
		'document_number',
	),
)); ?>
