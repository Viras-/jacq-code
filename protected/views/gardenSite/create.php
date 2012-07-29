<?php
$this->breadcrumbs=array(
	Yii::t('jacq', 'Garden Sites')=>array('index'),
	Yii::t('jacq', 'Create'),
);

$this->menu=array(
//	array('label'=>'List GardenSite', 'url'=>array('index')),
	array('label'=>Yii::t('jacq', 'Manage Garden Site'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('jacq', 'Create Garden Site'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>