<?php
$this->breadcrumbs=array(
	'Tree Record Files'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TreeRecordFile', 'url'=>array('index')),
	array('label'=>'Manage TreeRecordFile', 'url'=>array('admin')),
);
?>

<h1>Create TreeRecordFile</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>