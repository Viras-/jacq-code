<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'tree-record-file-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'year'); ?>
        <?php echo $form->textField($model, 'year', array('size' => 4, 'maxlength' => 4)); ?>
        <?php echo $form->error($model, 'year'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'document_number'); ?>
        <?php echo $form->textField($model, 'document_number', array('size' => 20, 'maxlength' => 20)); ?>
        <?php echo $form->error($model, 'document_number'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'fileName'); ?>
        <?php echo $form->fileField($model, 'fileName'); ?>
        <?php echo $form->error($model, 'fileName'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->