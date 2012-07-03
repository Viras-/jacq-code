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
            'model_botanicalObject' => $model_botanicalObject,
            'model_accessionNumber' => $model_accessionNumber
            )
        );

// Enable auto-completer for taxon field
$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
    'name' => 'scientificName',
    'sourceUrl' => 'index.php?r=autoComplete/taxon',
    // additional javascript options for the autocomplete plugin
    'options' => array(
        'minLength' => '2',
        'change' => "js:function( event, ui ) { $( '#BotanicalObject_taxon_id' ).val( ui.item.id ); }"
    ),
    'htmlOptions' => array(
        'style' => 'height:20px;'
    ),
));
