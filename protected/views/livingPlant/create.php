<?php
$this->breadcrumbs = array(
    'Living Plants' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List LivingPlant', 'url' => array('index')),
    array('label' => 'Manage LivingPlant', 'url' => array('admin')),
);
?>

<h1>Create LivingPlant</h1>

<?php
echo $this->renderPartial('_form', array(
    'model_acquisitionDate' => $model_acquisitionDate,
    'model_acquisitionEvent' => $model_acquisitionEvent,
    'model_separation' => $model_separation,
    'model_livingPlant' => $model_livingPlant,
    'model_botanicalObject' => $model_botanicalObject,
    'model_accessionNumber' => $model_accessionNumber,
    'model_citesNumber' => $model_citesNumber,
        )
);
