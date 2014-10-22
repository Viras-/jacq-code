<?php
/* @var $this IndexSeminumRevisionController */
/* @var $model IndexSeminumRevision */

$this->breadcrumbs = array(
    Yii::t('jacq', 'Index Seminum') => array('admin'),
    Yii::t('jacq', 'Manage'),
);

$this->menu = array(
    array(
        'label' => Yii::t('jacq', 'Create Revision'),
        'url' => array('create')
    ),
    array(
        'label' => Yii::t('jacq', 'Clear Links'),
        'url' => array('clear'),
        'linkOptions' => array(
            'confirm' => Yii::t('jacq', 'Are you sure you want to clear all Index Seminum links?')
        )
    ),
);
?>

<h1><?php echo CHtml::encode(Yii::t('jacq', 'Manage Index Seminum')); ?></h1>

<?php
// check if we are redirecting from the clear action
if ($cleared) {
    ?>
    <div class="flash-success">
        <?php echo CHtml::encode(Yii::t('jacq', 'Index Seminum links cleared.')); ?>
    </div>
    <?php
}
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'index-seminum-revision-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'user.username',
        'name',
        'timestamp',
        array(
            'class' => 'ISButtonColumn',
        ),
    ),
));
?>
