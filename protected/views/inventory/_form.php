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
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        ),
    ));
    ?>

    <?php echo $form->errorSummary($model_inventory); ?>

    <div class="row">
        <?php echo $form->labelEx($model_inventory, 'inventory_type_id'); ?>
        <?php
        echo $form->dropDownList($model_inventory, 'inventory_type_id', Html::listDataSorted(InventoryType::model()->findAll(), 'inventory_type_id', 'typeTranslated', true), array(
            'onchange' =>
            new CJavaScriptExpression("
                var selectedVal = $(this).val();
                $.ajax({
                    url: '" . $this->createUrl('inventory/ajaxInventoryType') . "',
                    data: {
                        inventory_type_id: selectedVal
                    }
                }).done(function(data) {
                    $('#inventory_form-content').html(data);
                });
                return false;
            ")
        ));
        ?>
        <?php echo $form->error($model_inventory, 'inventory_type_id'); ?>
    </div>

    <div class="row">
        <div id="inventory_form-content"></div>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model_inventory->isNewRecord ? Yii::t('jacq', 'Create') : Yii::t('jacq', 'Save')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->