<?php
/* @var $this IndexSeminumRevisionController */
/* @var $model IndexSeminumRevision */

$this->breadcrumbs = array(
    'Index Seminum Revisions' => array('admin'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Create Index Semimum Revision', 'url' => array('create')),
);
?>

<h1>Index Seminum Revision Management</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'index-seminum-revision-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'index_seminum_revision_id',
        'user_id',
        'name',
        'timestamp',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
