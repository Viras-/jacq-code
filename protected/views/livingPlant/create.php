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
echo $this->renderPartial('_form', $data );
