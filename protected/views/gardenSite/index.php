<?php
$this->breadcrumbs=array(
	'Garden Sites',
);

$this->menu=array(
	array('label'=>'Create GardenSite', 'url'=>array('create')),
	array('label'=>'Manage GardenSite', 'url'=>array('admin')),
);
?>

<h1>Garden Sites</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
