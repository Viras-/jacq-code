<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs = array(
    Yii::t('jacq', 'Inventories') => array('admin'),
    Yii::t('jacq', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('jacq', 'Manage Inventory'), 'url' => array('admin')),
);
?>

<h1><?php echo Yii::t('jacq', 'Create Inventory'); ?></h1>

<?php
// check if saving was successfull
if( $error_msg ) {
    ?>
    <div class="flash-success">
        <?php echo $error_msg; ?>
    </div>
    <?php
}
?>

<?php $this->renderPartial('_form', array('model_inventory' => $model_inventory)); ?>