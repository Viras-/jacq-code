<?php
$this->breadcrumbs = array(
    Yii::t('jacq', 'Living Plants') => array('index'),
    $model_livingPlant->accessionNumber => array('update', 'id' => $model_livingPlant->id),
    Yii::t('jacq', 'Update'),
);

$this->menu = array(
    array('label' => Yii::t('jacq', 'Create Living Plant'), 'url' => array('create')),
    array('label' => Yii::t('jacq', 'Manage Living Plant'), 'url' => array('admin')),
    array('label' => Yii::t('jacq', 'Work Label'), 'url' => Yii::app()->params['jacqJavaEEBaseUrl'] . 'rest/label/work/' . $model_livingPlant->id),
);
?>

<h1><?php echo Yii::t('jacq', 'Update Living Plant'); ?> <?php echo $model_livingPlant->accessionNumber; ?></h1>

<?php
// check if saving was successfull
if( $success ) {
    ?>
    <div class="flash-success">
        <?php echo CHtml::encode(Yii::t('jacq', 'Successfully saved')); ?>
    </div>
    <?php
}
?>

<?php
echo $this->renderPartial('_form', $data);
