<?php
$this->breadcrumbs=array(
	'Tree Record Files',
);

$this->menu=array(
	array('label'=>'Create TreeRecordFile', 'url'=>array('create')),
	array('label'=>'Manage TreeRecordFile', 'url'=>array('admin')),
);
?>

<h1>Tree Record Files</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
