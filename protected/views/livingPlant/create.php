<?php
$this->breadcrumbs=array(
	'Living Plants'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LivingPlant', 'url'=>array('index')),
	array('label'=>'Manage LivingPlant', 'url'=>array('admin')),
);
?>

<h1>Create LivingPlant</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>