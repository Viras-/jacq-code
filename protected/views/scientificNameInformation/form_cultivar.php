<?php
// check if we have a valid id already
if ($model_cultivar->cultivar_id > 0) {
    $model_form_id = $model_cultivar->cultivar_id;
}
else {
    // not exactly a clean definition, but for rendering the form it should work
    // real id is assigned on saving
    $model_form_id = "new_" . rand(0, 10000);
}
?>
<div id="cultivar_row_<?php echo $model_form_id; ?>" style="padding-bottom: 5px;">
    <?php
    echo CHtml::activeHiddenField($model_cultivar,
            "[$model_form_id]cultivar_id");
    echo CHtml::activeHiddenField($model_cultivar,
            "[$model_form_id]delete");
    echo CHtml::activeTextField($model_cultivar,
            "[$model_form_id]cultivar");

    echo CHtml::error($model_cultivar, "cultivar");
    ?>
    <?php
    echo CHtml::imageButton('images/delete.png',
        array(
            'onclick' => "
            $('#cultivar_row_{$model_form_id}').hide();
            $('#Cultivar_{$model_form_id}_delete').val(1);

            return false;",
            'style' => 'margin: 0;'
    ));
    ?>
</div>