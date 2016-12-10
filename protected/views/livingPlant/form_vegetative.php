<?php
// check if we have a valid id already
if ($model_derivativeVegetative->derivative_vegetative_id > 0) {
    $model_form_id = $model_derivativeVegetative->derivative_vegetative_id;
}
else {
    // not exactly a clean definition, but for rendering the form it should work
    // real id is assigned on saving
    $model_form_id = "new_" . rand(0, 10000);
}
?>
<div id="vegetative_row_<?php echo $model_form_id; ?>" style="padding-bottom: 5px;">
    <?php
    echo CHtml::activeHiddenField($model_derivativeVegetative, "[$model_form_id]derivative_vegetative_id");
    echo CHtml::activeHiddenField($model_derivativeVegetative, "[$model_form_id]delete");

    echo CHtml::activeTextField($model_derivativeVegetative, "[$model_form_id]accesion_number");
    echo CHtml::error($model_derivativeVegetative, "accesion_number");
    ?>
    <?php
    echo CHtml::imageButton('images/delete.png', array(
        'onclick' => "
            $('#vegetative_row_{$model_form_id}').hide();
            $('#Vegetative_{$model_form_id}_delete').val(1);

            return false;",
        'style' => 'margin: 0;'
    ));
    ?>
</div>
