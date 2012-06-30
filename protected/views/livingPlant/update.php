<?php
$this->breadcrumbs=array(
	'Living Plants'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List LivingPlant', 'url'=>array('index')),
	array('label'=>'Create LivingPlant', 'url'=>array('create')),
	array('label'=>'View LivingPlant', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage LivingPlant', 'url'=>array('admin')),
);
?>

<h1>Update LivingPlant <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>