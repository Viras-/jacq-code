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
    <div id="acquisitionEventSource_row_<?php echo $model_form_id; ?>" style="display: table-row;">
        <div style="display: table-cell;">
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
                'size' => 10,
            ),
            'value' => $model_acquisitionEventSource->source_date,
        ));
        echo CHtml::error($model_acquisitionEventSource, "source_date");
        ?>
        </div>
        <div style="display: table-cell;">
        <?php
        // Enable auto-completer for acquisition source field
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'name' => "AcquisitionEventSource[$model_form_id][acquisitionSource]",
            'sourceUrl' => $this->createUrl('autoComplete/acquisitionSource'),
            // additional javascript options for the autocomplete plugin
            'options' => array(
                'minLength' => '2',
                'change' => 'js:function( event, ui ) {
                        if( ui.item !== null ) {
                            $( "#AcquisitionEventSource_' . $model_form_id . '_acquisition_source_id" ).val( ui.item.id );
                        }
                    }',
            ),
            'value' => ($model_acquisitionEventSource->acquisitionSource != NULL) ? $model_acquisitionEventSource->acquisitionSource->name : '',
        ));
        ?>
        <?php echo CHtml::activeHiddenField($model_acquisitionEventSource, "[$model_form_id]acquisition_source_id"); ?>
        <?php echo CHtml::error($model_acquisitionEventSource, "[$model_form_id]acquisition_source_id"); ?>
        </div>
        <div style="display: table-cell;">
        <?php
        echo CHtml::imageButton('images/delete.png', array(
            'onclick' => "
                $('#acquisitionEventSource_row_{$model_form_id}').hide();
                $('#AcquisitionEventSource_{$model_form_id}_delete').val(1);

                return false;"
        ));
        ?>
        </div>
    </div>
