<?php
$this->breadcrumbs = array(
    Yii::t('jacq', 'Living Plants') => array('index'),
    $model_livingPlant->id => array('update', 'id' => $model_livingPlant->id),
    Yii::t('jacq', 'Update'),
);

$this->menu = array(
    array('label' => Yii::t('jacq', 'Create Living Plant'), 'url' => array('create')),
    array('label' => Yii::t('jacq', 'Manage Living Plant'), 'url' => array('admin')),
);
?>

<h1><?php echo Yii::t('jacq', 'Update Living Plant'); ?> <?php echo $model_livingPlant->id; ?></h1>

<?php
echo $this->renderPartial('_form', $data);
