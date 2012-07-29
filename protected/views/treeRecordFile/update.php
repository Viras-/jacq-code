<?php
$this->breadcrumbs=array(
	Yii::t('jacq', 'Tree Record Files')=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	Yii::t('jacq', 'Update'),
);

$this->menu=array(
//	array('label'=>'List TreeRecordFile', 'url'=>array('index')),
	array('label'=>Yii::t('jacq', 'Create Tree Record File'), 'url'=>array('create')),
//	array('label'=>'View TreeRecordFile', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('jacq', 'Manage Tree Record File'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('jacq', 'Update Tree Record File'); ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>