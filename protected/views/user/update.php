<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	Yii::t('jacq','Users')=>array('index'),
	$model->id=>array('update','id'=>$model->id),
	Yii::t('jacq','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('jacq','Create User'), 'url'=>array('create')),
	array('label'=>Yii::t('jacq','Manage User'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('jacq','Update User'); ?> <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>