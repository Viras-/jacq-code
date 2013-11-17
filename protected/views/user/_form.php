<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

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

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password', array('size' => 60, 'maxlength' => 64)); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'salt'); ?>
        <?php echo $form->textField($model, 'salt', array('size' => 60, 'maxlength' => 64)); ?>
        <?php echo $form->error($model, 'salt'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'user_type_id'); ?>
        <?php echo $form->dropDownList($model, 'user_type_id', CHtml::listData(UserType::model()->findAll(), 'user_type_id', 'typeTranslated')); ?>
        <?php echo $form->error($model, 'user_type_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'employment_type_id'); ?>
        <?php echo $form->dropDownList($model, 'employment_type_id', CHtml::listData(EmploymentType::model()->findAll(), 'employment_type_id', 'typeTranslated')); ?>
        <?php echo $form->error($model, 'employment_type_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'title_prefix'); ?>
        <?php echo $form->textField($model, 'title_prefix', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'title_prefix'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'firstname'); ?>
        <?php echo $form->textField($model, 'firstname', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'firstname'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'lastname'); ?>
        <?php echo $form->textField($model, 'lastname', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'lastname'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'title_suffix'); ?>
        <?php echo $form->textField($model, 'title_suffix', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'title_suffix'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'birthdate'); ?>
        <?php echo $form->textField($model, 'birthdate'); ?>
        <?php echo $form->error($model, 'birthdate'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'organisation_id'); ?>
        <?php echo $form->dropDownList($model, 'organisation_id', CHtml::listData(Organisation::model()->findAll(), 'id', 'description')); ?>
        <?php echo $form->error($model, 'organisation_id'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->