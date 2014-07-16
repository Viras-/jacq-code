<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = array(
    Yii::t('jacq', 'Users') => array('index'),
    $model->id => array('update', 'id' => $model->id),
    Yii::t('jacq', 'Update'),
);

$this->menu = array();
?>

<h1><?php echo Yii::t('jacq', 'Update Profile'); ?> <?php echo $model->id; ?></h1>


<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo CHtml::encode($model->username); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'newPassword'); ?>
        <?php echo $form->passwordField($model, 'newPassword', array('size' => 60, 'maxlength' => 64)); ?>
        <?php echo $form->error($model, 'newPassword'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('jacq', 'Update')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
