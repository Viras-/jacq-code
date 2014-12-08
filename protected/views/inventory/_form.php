<?php
/* @var $this InventoryController */
/* @var $model Inventory */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'inventory-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model_inventory); ?>

    <div class="row">
        <?php echo $form->labelEx($model_inventory, 'inventory_type_id'); ?>
        <?php echo $form->dropDownList($model_inventory, 'inventory_type_id', Html::listDataSorted(Cultivar::model()->findAll(), 'inventory_type_id', 'type')); ?>
        <?php echo $form->error($model_inventory, 'inventory_type_id'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model_inventory->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->