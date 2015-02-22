<?php
// check if we have a valid id already
if ($model_separation->id > 0) {
    $model_form_id = $model_separation->id;
}
else {
    // not exactly a clean definition, but for rendering the form it should work
    // real id is assigned on saving
    $model_form_id = "new_" . rand(0, 10000);
}
?>
<div id="separation_row_<?php echo $model_form_id; ?>" style="padding-bottom: 5px;">
    <div class="separation_td">
        <?php
        echo CHtml::activeHiddenField($model_separation,
                "[$model_form_id]id");
        echo CHtml::activeHiddenField($model_separation,
                "[$model_form_id]delete");

        echo CHtml::activeDropDownList($model_separation, "[$model_form_id]separation_type_id", Html::listDataSorted(SeparationType::model()->findAll(), "id", "typeTranslated", true));
        echo CHtml::error($model_separation, "separation_type_id");
        ?>
    </div>
    <div class="separation_td">
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => "Separation[$model_form_id][date]",
            // additional javascript options for the date picker plugin
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'changeMonth' => true,
                'changeYear' => true,
            ),
            'htmlOptions' => array(
            ),
            'value' => $model_separation->date,
        ));
        echo CHtml::error($model_separation, 'date');
        ?>
    </div>
    <div class="separation_td">
        <?php
        echo CHtml::activeTextField($model_separation, "[$model_form_id]annotation");
        echo CHtml::error($model_separation, 'annotation');
        ?>
    </div>
    <div style="display: inline-block;">
        <?php
        echo CHtml::imageButton('images/delete.png',
            array(
                'onclick' => "
                $('#separation_row_{$model_form_id}').hide();
                $('#Separation_{$model_form_id}_delete').val(1);

                return false;",
                'style' => 'margin: 0;'
        ));
        ?>
    </div>
</div>
