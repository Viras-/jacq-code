<?php echo Html::hiddenField('InventoryInventory[inventory_type_id]', $model_inventoryType->inventory_type_id); ?>

<div class="row">
    <?php echo Html::label(BotanicalObject::model()->getAttributeLabel('organisation_id'), 'InventoryInventory[organisation_id]'); ?>
    <?php echo Html::dropDownList('InventoryInventory[organisation_id]', '', Html::listDataSorted(Organisation::model()->findAll(), 'id', 'description', true)); ?>
</div>

<div class="row">
    <?php echo Html::label(Yii::t('jacq', 'Separate non found entries'), 'InventoryInventory[separate_not_found]'); ?>
    <?php echo Html::checkBox('InventoryInventory[separate_not_found]'); ?>
</div>

<div class="row">
    <?php echo Html::fileField('InventoryInventory[inventory_file]'); ?>
</div>
