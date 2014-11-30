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
        'user_id',
        'inventory_type_id',
        'timestamp',
    ),
));
