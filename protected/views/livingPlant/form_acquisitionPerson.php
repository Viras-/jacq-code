<?php
// check if we have a valid id already
if ($model_acquisitionPerson->id > 0) {
    $model_form_id = $model_acquisitionPerson->id;
}
else {
    // not exactly a clean definition, but for rendering the form it should work
    // real id is assigned on saving
    $model_form_id = "new_" . rand(0, 10000);
}
?>
<div id="acquisitionPerson_row_<?php echo $model_form_id; ?>" style="padding-bottom: 5px;">
    <?php
    echo CHtml::activeHiddenField($model_acquisitionPerson,
            "[$model_form_id]id");
    echo CHtml::activeHiddenField($model_acquisitionPerson,
            "[$model_form_id]delete");

    // Add addition field for new collector
    $this->widget('zii.widgets.jui.CJuiAutoComplete',
            array(
                'name' => "Person[$model_form_id][name]",
                'sourceUrl' => 'index.php?r=autoComplete/person',
                // additional javascript options for the autocomplete plugin
                'options' => array(
                    'minLength' => '2',
                ),
                'value' => $model_acquisitionPerson->name,
                'htmlOptions' => array(
                    'style' => 'margin: 0;'
                )
    ));

    echo CHtml::error($model_acquisitionPerson, "name");
    ?>
    <?php
    echo CHtml::imageButton('images/delete.png',
        array(
            'onclick' => "
            $('#acquisitionPerson_row_{$model_form_id}').hide();
            $('#Person_{$model_form_id}_delete').val(1);

            return false;",
            'style' => 'margin: 0;'
    ));
    ?>
</div>