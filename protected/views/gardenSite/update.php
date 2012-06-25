<?php
$this->breadcrumbs=array(
	'Garden Sites'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GardenSite', 'url'=>array('index')),
	array('label'=>'Create GardenSite', 'url'=>array('create')),
	array('label'=>'View GardenSite', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GardenSite', 'url'=>array('admin')),
);
?>

<h1>Update GardenSite <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>