<?php
/* @var $this InventoryController */
/* @var $data Inventory */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('inventory_id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->inventory_id), array('view', 'id' => $data->inventory_id)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
    <?php echo CHtml::encode($data->user_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('inventory_type_id')); ?>:</b>
    <?php echo CHtml::encode($data->inventory_type_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('timestamp')); ?>:</b>
    <?php echo CHtml::encode($data->timestamp); ?>
    <br />


</div>