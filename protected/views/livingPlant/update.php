<?php
$this->breadcrumbs=array(
	'Living Plants'=>array('index'),
	$model_livingPlant->id=>array('view','id'=>$model_livingPlant->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List LivingPlant', 'url'=>array('index')),
	array('label'=>'Create LivingPlant', 'url'=>array('create')),
	array('label'=>'View LivingPlant', 'url'=>array('view', 'id'=>$model_livingPlant->id)),
	array('label'=>'Manage LivingPlant', 'url'=>array('admin')),
);
?>

<h1>Update LivingPlant <?php echo $model_livingPlant->id; ?></h1>

<?php
echo $this->renderPartial('_form', 
        array(
            'model_acquisitionDate' => $model_acquisitionDate,
            'model_acquisitionEvent' => $model_acquisitionEvent,
            'model_separation' => $model_separation,
            'model_livingPlant' => $model_livingPlant,
            'model_botanicalObject' => $model_botanicalObject
            )
        );
