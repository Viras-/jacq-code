<?php
// check if we have a valid id already, if not skip the hidden field
if( $model_acquisitionEventSource->acquisition_event_source_id > 0 ) {
    $model_form_id = $model_acquisitionEventSource->acquisition_event_source_id;
}
else {
    // not exactly a clean definition, but for rendering the form it should work
    // real id is assigned on saving
    $model_form_id = "new_" . rand(0, 10000);
}
?>
    <tr id="acquisitionEventSource_row_<?php echo $model_form_id; ?>">
        <td width="20%">
        <?php
        echo CHtml::activeHiddenField($model_acquisitionEventSource, "[$model_form_id]acquisition_event_source_id");
        echo CHtml::activeHiddenField($model_acquisitionEventSource, "[$model_form_id]delete");

        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => "AcquisitionEventSource[$model_form_id][source_date]",
            // additional javascript options for the date picker plugin
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'changeMonth' => true,
                'changeYear' => true
            ),
            'htmlOptions' => array(

            ),
            'value' => $model_acquisitionEventSource->source_date,
        ));
        echo CHtml::error($model_acquisitionEventSource, "source_date");
        ?>
        </td>
        <td>
            <!-- autocompleter for aquisition source name -->
        </td>
        <td>
        <?php
        echo CHtml::imageButton('images/delete.png', array(
            'onclick' => "
                $('#acquisitionEventSource_row_{$model_form_id}').hide();
                $('#acquisitionEventSource_{$model_form_id}_delete').val(1);

                return false;"
        ));
        ?>
        </td>
    </tr>
