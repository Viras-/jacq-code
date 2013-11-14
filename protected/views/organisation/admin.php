<?php
$this->breadcrumbs = array(
    Yii::t('jacq', 'Garden Sites') => array('index'),
    Yii::t('jacq', 'Manage'),
);

$this->menu = array(
//	array('label'=>'List GardenSite', 'url'=>array('index')),
    array('label' => Yii::t('jacq', 'Create Garden Site'), 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('garden-site-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('jacq', 'Manage Garden Sites'); ?></h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'garden-site-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'description',
        'department',
        'parentOrganisation.description',
        'gardener.username',
        array(
            'class' => 'ORButtonColumn',
        ),
    ),
));
