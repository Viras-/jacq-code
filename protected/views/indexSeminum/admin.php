<?php
/* @var $this IndexSeminumRevisionController */
/* @var $model IndexSeminumRevision */

$this->breadcrumbs = array(
    Yii::t('jacq', 'Index Seminum') => array('admin'),
    Yii::t('jacq', 'Manage'),
);

$this->menu = array(
    array('label' => Yii::t('jacq', 'Create Index Seminum'), 'url' => array('create')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('jacq', 'Manage Index Seminum')); ?></h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'index-seminum-revision-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'index_seminum_revision_id',
        'user.username',
        'name',
        'timestamp',
        array(
            'class' => 'ISButtonColumn',
        ),
    ),
));
?>
