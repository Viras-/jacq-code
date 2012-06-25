<?php
$this->breadcrumbs=array(
	'Garden Sites'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GardenSite', 'url'=>array('index')),
	array('label'=>'Manage GardenSite', 'url'=>array('admin')),
);
?>

<h1>Create GardenSite</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>