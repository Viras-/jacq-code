<?php
$this->breadcrumbs=array(
	Yii::t('jacq', 'Garden Sites')=>array('index'),
	$model->id=>array('update','id'=>$model->id),
	Yii::t('jacq', 'Update'),
);

$this->menu=array(
//	array('label'=>'List GardenSite', 'url'=>array('index')),
	array('label'=>Yii::t('jacq', 'Create Garden Site'), 'url'=>array('create')),
//	array('label'=>'View GardenSite', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('jacq', 'Manage Garden Site'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('jacq', 'Update Garden Site'); ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>