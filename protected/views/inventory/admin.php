<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs = array(
    'Inventories' => array('admin'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Create Inventory', 'url' => array('create')),
);

?>

<h1>Manage Inventories</h1>

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
