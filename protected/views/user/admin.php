<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	Yii::t('jacq','Users')=>array('index'),
	Yii::t('jacq','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('jacq','Create User'), 'url'=>array('create')),
);
?>

<h1><?php echo Yii::t('jacq','Manage Users'); ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'username',
		'firstname',
		'lastname',
		/*
		'password',
		'salt',
		'user_type_id',
		'employment_type_id',
		'title_prefix',
		'title_suffix',
		'birthdate',
		'organisation_id',
		*/
		array(
			'class'=>'UButtonColumn',
		),
	),
)); ?>
