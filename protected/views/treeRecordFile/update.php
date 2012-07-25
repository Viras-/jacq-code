<?php
$this->breadcrumbs=array(
	'Tree Record Files'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TreeRecordFile', 'url'=>array('index')),
	array('label'=>'Create TreeRecordFile', 'url'=>array('create')),
	array('label'=>'View TreeRecordFile', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TreeRecordFile', 'url'=>array('admin')),
);
?>

<h1>Update TreeRecordFile <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>