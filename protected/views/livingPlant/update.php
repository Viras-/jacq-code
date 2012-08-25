<?php
$this->breadcrumbs=array(
	Yii::t('jacq', 'Living Plants')=>array('index'),
	$model_livingPlant->id=>array('view','id'=>$model_livingPlant->id),
	Yii::t('jacq', 'Update'),
);

$this->menu=array(
//	array('label'=>'List LivingPlant', 'url'=>array('index')),
	array('label'=>Yii::t('jacq', 'Create Living Plant'), 'url'=>array('create')),
//	array('label'=>'View LivingPlant', 'url'=>array('view', 'id'=>$model_livingPlant->id)),
	array('label'=>Yii::t('jacq', 'Manage Living Plant'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('jacq', 'Update Living Plant'); ?> <?php echo $model_livingPlant->id; ?></h1>

<?php
echo $this->renderPartial('_form', 
        array(
            'model_acquisitionDate' => $model_acquisitionDate,
            'model_acquisitionEvent' => $model_acquisitionEvent,
            'model_separation' => $model_separation,
            'model_livingPlant' => $model_livingPlant,
            'model_botanicalObject' => $model_botanicalObject,
            'model_accessionNumber' => $model_accessionNumber,
            )
        );
