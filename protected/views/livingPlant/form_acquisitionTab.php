<!-- acquisition date -->
<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php echo $form->labelEx($model_acquisitionDate, 'acquisition_date'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'AcquisitionDate[date]',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd',
                        'changeMonth' => true,
                        'changeYear' => true
                    ),
                    'htmlOptions' => array(
                    ),
                    'value' => $model_acquisitionDate->date,
                ));
                ?>
                <?php echo $form->error($model_acquisitionDate, 'date'); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model_acquisitionDate, 'custom'); ?>
                <?php echo $form->textField($model_acquisitionDate, 'custom'); ?>
                <?php echo $form->error($model_acquisitionDate, 'custom'); ?>
            </td>
        </tr>
    </table>
</div>
<!-- acqusition persons -->
<div class="row">
    <?php require("form_acquisitionPersons.php"); ?>
</div>
<!-- acquisition number -->
<div class="row">
    <?php echo $form->labelEx($model_acquisitionEvent, 'number'); ?>
    <?php echo $form->textField($model_acquisitionEvent, 'number'); ?>
    <?php echo $form->error($model_acquisitionEvent, 'number'); ?>
</div>
<hr />
<!-- acquisition location -->
<div class="row">
    <?php echo $form->labelEx($model_acquisitionEvent, 'location_id'); ?>
    <?php
    // Enable auto-completer for taxon field
    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
        'name' => 'locationName',
        'sourceUrl' => 'index.php?r=autoComplete/location&geonames=true',
        // additional javascript options for the autocomplete plugin
        'options' => array(
            'minLength' => '2',
            'select' => 'js:function( event, ui ) {
                    if( typeof ui.item !== "undefined" ) {
                        $( "#AcquisitionEvent_location_id" ).val( ui.item.id );
                    }
                }',
        /* 'open' => 'js:function(event, ui) {
          var liItem = $("<li class=\'ui-menu-item\' role=\'menuitem\'></li>")
          var aItem = $( "<a class=\'ui-corner-all\'>More...</a>" );
          aItem.hover( function() { $(this).addClass( "ui-state-hover" ); }, function() { $(this).removeClass( "ui-state-hover" ); } );
          aItem.click( function(event, ui) { $( "#locationName" ).autocomplete( "option", "source", "index.php?r=autoComplete/location&geonames=true" ); return false; } );

          liItem.append( aItem );
          $(this).autocomplete( "widget" ).append( liItem );
          }', */
        /* 'search' => 'js:function(event, ui) {
          $( "#AcquisitionEvent_location_id" ).val("");
          return true;
          }' */
        ),
        'value' => ($model_acquisitionEvent->location != null) ? $model_acquisitionEvent->location->location : '',
        'htmlOptions' => array(
            'onkeypress' => '$( "#AcquisitionEvent_location_id" ).val("");'
        ),
    ));
    ?>
    <?php echo $form->hiddenField($model_acquisitionEvent, 'location_id'); ?>
    <?php echo $form->error($model_acquisitionEvent, 'location_id'); ?>
</div>
<!-- acquisition altitude -->
<div class="row">
    <?php echo $form->labelEx($model_locationCoordinates, 'altitude'); ?>
    <?php echo $form->textField($model_locationCoordinates, 'altitude_min', array('size' => 5)); ?>
    -
    <?php echo $form->textField($model_locationCoordinates, 'altitude_max', array('size' => 5)); ?>
    <?php echo $form->error($model_locationCoordinates, 'altitude'); ?>
</div>
<!-- acquisition coordinates -->
<div class="row">
    <table>
        <tr>
            <td>
                <?php echo $form->labelEx($model_locationCoordinates, 'latitude'); ?>
                <?php echo $form->textField($model_locationCoordinates, 'latitude_degrees', array('size' => 3)); ?>
                <?php echo $form->textField($model_locationCoordinates, 'latitude_minutes', array('size' => 2)); ?>
                <?php echo $form->textField($model_locationCoordinates, 'latitude_seconds', array('size' => 2)); ?>
                <?php echo $form->dropDownList($model_locationCoordinates, 'latitude_half', array('N' => 'N', 'S' => 'S')); ?>
                <?php echo $form->error($model_locationCoordinates, 'latitude_degrees'); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model_locationCoordinates, 'longitude'); ?>
                <?php echo $form->textField($model_locationCoordinates, 'longitude_degrees', array('size' => 3)); ?>
                <?php echo $form->textField($model_locationCoordinates, 'longitude_minutes', array('size' => 2)); ?>
                <?php echo $form->textField($model_locationCoordinates, 'longitude_seconds', array('size' => 2)); ?>
                <?php echo $form->dropDownList($model_locationCoordinates, 'longitude_half', array('E' => 'E', 'W' => 'W')); ?>
                <?php echo $form->error($model_locationCoordinates, 'longitude_degrees'); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model_locationCoordinates, 'exactness'); ?>
                <?php echo $form->textField($model_locationCoordinates, 'exactness', array('size' => 5)); ?>
                <?php echo $form->error($model_locationCoordinates, 'exactness'); ?>
            </td>
        </tr>
    </table>
</div>
<!-- acquisition annotation -->
<div class="row">
    <?php echo $form->labelEx($model_acquisitionEvent, 'annotation'); ?>
    <?php echo $form->textArea($model_acquisitionEvent, 'annotation', array('style' => 'width: 100%;')); ?>
    <?php echo $form->error($model_acquisitionEvent, 'annotation'); ?>
</div>
<!-- index seminum -->
<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php echo $form->labelEx($model_livingPlant, 'index_seminum'); ?>
                <?php echo $form->checkBox($model_livingPlant, 'index_seminum'); ?>
                <?php echo $form->error($model_livingPlant, 'index_seminum'); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model_livingPlant, 'index_seminum_type_id'); ?>
                <?php echo $form->dropDownList($model_livingPlant, 'index_seminum_type_id', CHtml::listData(IndexSeminumType::model()->findAll(), 'id', 'type')); ?>
                <?php echo $form->error($model_livingPlant, 'index_seminum_type_id'); ?>
            </td>
        </tr>
    </table>
</div>
<!-- habitat -->
<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'habitat'); ?>
    <?php echo $form->textArea($model_botanicalObject, 'habitat', array('style' => 'width: 100%;')); ?>
    <?php echo $form->error($model_botanicalObject, 'habitat'); ?>
</div>
