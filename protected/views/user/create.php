<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	Yii::t('jacq','Users')=>array('index'),
	Yii::t('jacq','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('jacq','Manage User'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('jacq','Create User'); ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>