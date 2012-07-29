<?php
$this->breadcrumbs=array(
	Yii::t('jacq', 'Tree Record Files')=>array('index'),
	Yii::t('jacq', 'Create'),
);

$this->menu=array(
//	array('label'=>'List TreeRecordFile', 'url'=>array('index')),
	array('label'=>Yii::t('jacq', 'Manage Tree Record File'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('jacq', 'Create Tree Record File'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>