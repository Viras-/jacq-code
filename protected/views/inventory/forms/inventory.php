<?php echo Html::hiddenField('Inventory[inventory_type_id]', $model_inventoryType->inventory_type_id); ?>

<div class="row">
    <?php echo Html::label(BotanicalObject::model()->getAttributeLabel('organisation_id'), 'Inventory[organisation_id]'); ?>
    <?php echo Html::textField('Inventory[organisation_name]', '', array('readonly' => 'readonly')); ?>
    <?php
    echo Html::imageButton(
            'images/magnifier.png', array(
                'onclick' =>
                new CJavaScriptExpression("
                    $('#organisation_select_dialog').dialog('open');
                    return false
                ")
            )
    );
    ?>
    <?php echo Html::hiddenField('Inventory[organisation_id]', ''); ?>
</div>

<div class="row">
    <?php echo Html::fileField('Inventory[inventory_file]'); ?>
</div>
