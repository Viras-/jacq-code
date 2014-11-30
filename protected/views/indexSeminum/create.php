<?php
/* @var $this IndexSeminumRevisionController */
/* @var $model IndexSeminumRevision */

$this->breadcrumbs = array(
    Yii::t('jacq', 'Index Seminum') => array('admin'),
    Yii::t('jacq', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('jacq', 'Manage Index Seminum'), 'url' => array('admin')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('jacq', 'Create Index Seminum')); ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>