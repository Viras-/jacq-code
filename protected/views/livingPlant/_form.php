<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'living-plant-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

<?php //echo $form->errorSummary($model_acquisitionDate, $model_acquisitionEvent, $model_livingPlant,$model_botanicalObject);  ?>

    <fieldset>
        <legend>acquisition</legend>
        <?php
        require('form_acquisitionDate.php');
        require('form_acquisitionEvent.php');
        ?>
    </fieldset>
    <fieldset>
        <legend>separation</legend>
        <?php
        require('form_separation.php');
        ?>
    </fieldset>
    <fieldset>
        <legend>living plant</legend>
        <?php
        require('form_botanicalObject.php');
        require('form_livingPlant.php');
        ?>
    </fieldset>
    <fieldset>
        <legend>tree record</legend>
        <?php
        require('form_treeRecord.php');
        ?>
    </fieldset>

    <div class="row buttons">
    <?php echo CHtml::submitButton($model_livingPlant->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->