<!-- referencing inventory entriy -->
<div class="row">
    <?php
// Grid view of all related inventory objects
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'livingplant-inventory-object-grid',
        'dataProvider' => $model_inventoryObject->search(),
        'columns' => array(
            'renderedMessage',
            'timestamp',
            array(
                'class' => 'LBInvButtonColumn',
            ),
        ),
    ));
    ?>
</div>
