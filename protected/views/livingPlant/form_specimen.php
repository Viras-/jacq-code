<?php
// check if we have a valid id already
if ($model_specimen->specimen_id > 0) {
    $model_form_id = $model_specimen->specimen_id;
}
else {
    // not exactly a clean definition, but for rendering the form it should work
    // real id is assigned on saving
    $model_form_id = "new_" . rand(0, 10000);
}
?>
<div id="specimen_row_<?php echo $model_form_id; ?>" style="padding-bottom: 5px;">
    <?php
    echo CHtml::activeHiddenField($model_specimen, "[$model_form_id]specimen_id");
    echo CHtml::activeHiddenField($model_specimen, "[$model_form_id]delete");
    echo CHtml::activeTextField($model_specimen, "[$model_form_id]herbar_number");

    echo CHtml::error($model_specimen, "herbar_number");
    ?>
    <?php
    echo CHtml::imageButton('images/delete.png', array(
        'onclick' => "
            $('#specimen_row_{$model_form_id}').hide();
            $('#Specimen_{$model_form_id}_delete').val(1);

            return false;",
        'style' => 'margin: 0;'
    ));
    ?>
</div>
