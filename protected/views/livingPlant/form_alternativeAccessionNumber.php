<?php
// check if we have a valid id already
if ($model_alternativeAccessionNumber->id > 0) {
    $model_form_id = $model_alternativeAccessionNumber->id;
}
else {
    // not exactly a clean definition, but for rendering the form it should work
    // real id is assigned on saving
    $model_form_id = "new_" . rand(0, 10000);
}
?>
<div id="alternativeAccessionNumber_row_<?php echo $model_form_id; ?>" style="padding-bottom: 5px;">
    <?php
    echo CHtml::activeHiddenField($model_alternativeAccessionNumber,
            "[$model_form_id]id");
    echo CHtml::activeHiddenField($model_alternativeAccessionNumber,
            "[$model_form_id]delete");
    echo CHtml::activeTextField($model_alternativeAccessionNumber,
            "[$model_form_id]number");

    echo CHtml::error($model_alternativeAccessionNumber, "number");
    ?>
    <?php
    echo CHtml::imageButton('images/delete.png',
        array(
            'onclick' => "
            $('#alternativeAccessionNumber_row_{$model_form_id}').hide();
            $('#AlternativeAccessionNumber_{$model_form_id}_delete').val(1);

            return false;",
            'style' => 'margin: 0;'
    ));
    ?>
</div>