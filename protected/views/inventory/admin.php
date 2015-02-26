<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs = array(
    Yii::t('jacq', 'Inventories') => array('admin'),
    Yii::t('jacq', 'Manage'),
);

$this->menu = array(
    array('label' => Yii::t('jacq', 'Create Inventory'), 'url' => array('create')),
);

?>

<h1><?php echo Yii::t('jacq', 'Manage Inventories' ); ?></h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'inventory-grid',
    'dataProvider' => $model_inventory->search(),
    'filter' => $model_inventory,
    'columns' => array(
        'inventory_id',
        'user.username',
        'inventoryType.type',
        'timestamp',
        array(
            'class' => 'InvButtonColumn',
        ),
    ),
));
