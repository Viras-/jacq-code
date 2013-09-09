<?php
$this->breadcrumbs = array(
    Yii::t('jacq', 'Tree Record Files') => array('index'),
    Yii::t('jacq', 'Manage'),
);

$this->menu = array(
//	array('label'=>'List TreeRecordFile', 'url'=>array('index')),
    array('label' => Yii::t('jacq', 'Create Tree Record File'), 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('tree-record-file-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('jacq', 'Manage Tree Record Files'); ?></h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'tree-record-file-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'year',
        'name',
        'document_number',
        array(
            'class' => 'TRFButtonColumn',
        ),
    ),
));
?>
