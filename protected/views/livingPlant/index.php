<?php
$this->breadcrumbs=array(
	'Living Plants',
);

$this->menu=array(
	array('label'=>'Create LivingPlant', 'url'=>array('create')),
	array('label'=>'Manage LivingPlant', 'url'=>array('admin')),
);
?>

<h1>Living Plants</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
