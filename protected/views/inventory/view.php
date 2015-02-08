<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs = array(
    'Inventories' => array('admin'),
    $model_inventory->inventory_id,
);

$this->menu = array(
    array('label' => 'Create Inventory', 'url' => array('create')),
    array('label' => 'Manage Inventory', 'url' => array('admin')),
);
?>

<h1>View Inventory #<?php echo $model_inventory->inventory_id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model_inventory,
    'attributes' => array(
        'inventory_id',
        'user.username',
        'inventoryType.type',
        'timestamp',
    ),
));

// Grid view of all related inventory objects
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'inventory-object-grid',
    'dataProvider' => $model_inventoryObject->search(),
    'columns' => array(
        'inventory_object_id',
        'botanical_object_id',
        'renderedMessage',
        'timestamp',
        array(
            'class' => 'InvObjButtonColumn',
        ),
    ),
));
