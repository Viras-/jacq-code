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

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#inventory-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Inventories</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'inventory-grid',
    'dataProvider' => $model_inventory->search(),
    'filter' => $model_inventory,
    'columns' => array(
        'inventory_id',
        'user_id',
        'inventory_type_id',
        'timestamp',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
