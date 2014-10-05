<?php
/* @var $this IndexSeminumRevisionController */
/* @var $model IndexSeminumRevision */

$this->breadcrumbs=array(
	'Index Seminum Revisions'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage IndexSeminumRevision', 'url'=>array('admin')),
);
?>

<h1>Create IndexSeminumRevision</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>