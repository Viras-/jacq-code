<?php
$this->breadcrumbs=array(
	Yii::t('jacq', 'Garden Sites')=>array('index'),
	Yii::t('jacq', 'Manage'),
);

$this->menu=array(
//	array('label'=>'List GardenSite', 'url'=>array('index')),
	array('label'=>Yii::t('jacq', 'Create Garden Site'), 'url'=>array('create')),
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

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'garden-site-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'description',
		'department',
		'greenhouse',
		'parent_organisation_id',
		'gardener_id',
		array(
			'class'=>'ORButtonColumn',
		),
	),
));
