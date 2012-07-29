<?php
$this->breadcrumbs = array(
    Yii::t('jacq', 'Living Plants') => array('index'),
    Yii::t('jacq', 'Create'),
);

$this->menu = array(
//    array('label' => 'List LivingPlant', 'url' => array('index')),
    array('label' => Yii::t('jacq', 'Manage Living Plant'), 'url' => array('admin')),
);
?>

<h1><?php echo Yii::t('jacq', 'Create Living Plant'); ?></h1>

<?php
echo $this->renderPartial('_form', array(
    'model_acquisitionDate' => $model_acquisitionDate,
    'model_acquisitionEvent' => $model_acquisitionEvent,
    'model_separation' => $model_separation,
    'model_livingPlant' => $model_livingPlant,
    'model_botanicalObject' => $model_botanicalObject,
    'model_accessionNumber' => $model_accessionNumber,
    'model_citesNumber' => $model_citesNumber,
        )
);
