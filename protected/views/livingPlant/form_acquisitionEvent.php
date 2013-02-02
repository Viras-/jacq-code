<div class="row">
    <?php echo $form->labelEx($model_acquisitionEvent, 'acquisition_type_id'); ?>
    <?php echo $form->dropDownList($model_acquisitionEvent, 'acquisition_type_id', CHtml::listData(AcquisitionType::model()->findAll(), 'id', 'type')); ?>
    <?php echo $form->error($model_acquisitionEvent, 'acquisition_type_id'); ?>
</div>

<div class="row">
    <?php require("form_acquisitionPersons.php"); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_acquisitionEvent, 'number'); ?>
    <?php echo $form->textField($model_acquisitionEvent, 'number'); ?>
    <?php echo $form->error($model_acquisitionEvent, 'number'); ?>
</div>
<hr />
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
            /*'open' => 'js:function(event, ui) {
                    var liItem = $("<li class=\'ui-menu-item\' role=\'menuitem\'></li>")
                    var aItem = $( "<a class=\'ui-corner-all\'>More...</a>" );
                    aItem.hover( function() { $(this).addClass( "ui-state-hover" ); }, function() { $(this).removeClass( "ui-state-hover" ); } );
                    aItem.click( function(event, ui) { $( "#locationName" ).autocomplete( "option", "source", "index.php?r=autoComplete/location&geonames=true" ); return false; } );

                    liItem.append( aItem );
                    $(this).autocomplete( "widget" ).append( liItem );
                }',*/
            /*'search' => 'js:function(event, ui) {
                    $( "#AcquisitionEvent_location_id" ).val("");
                    return true;
                }'*/
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

<div class="row">
    <?php echo $form->labelEx($model_locationCoordinates, 'altitude'); ?>
    <?php echo $form->textField($model_locationCoordinates, 'altitude_min', array( 'size' => 5 ) ); ?>
    -
    <?php echo $form->textField($model_locationCoordinates, 'altitude_max', array( 'size' => 5 )); ?>
    <?php echo $form->error($model_locationCoordinates, 'altitude'); ?>
</div>

<div class="row">
    <table>
        <tr>
            <td>
            <?php echo $form->labelEx($model_locationCoordinates, 'latitude'); ?>
            <?php echo $form->textField($model_locationCoordinates, 'latitude_degrees', array( 'size' => 3 ) ); ?>
            <?php echo $form->textField($model_locationCoordinates, 'latitude_minutes', array( 'size' => 2 ) ); ?>
            <?php echo $form->textField($model_locationCoordinates, 'latitude_seconds', array( 'size' => 2 ) ); ?>
            <?php echo $form->dropDownList($model_locationCoordinates, 'latitude_half', array( 'N' => 'N', 'S' => 'S' ) ); ?>
            <?php echo $form->error($model_locationCoordinates, 'latitude_degrees'); ?>
            </td>
            <td>
            <?php echo $form->labelEx($model_locationCoordinates, 'longitude'); ?>
            <?php echo $form->textField($model_locationCoordinates, 'longitude_degrees', array( 'size' => 3 ) ); ?>
            <?php echo $form->textField($model_locationCoordinates, 'longitude_minutes', array( 'size' => 2 ) ); ?>
            <?php echo $form->textField($model_locationCoordinates, 'longitude_seconds', array( 'size' => 2 ) ); ?>
            <?php echo $form->dropDownList($model_locationCoordinates, 'longitude_half', array( 'E' => 'E', 'W' => 'W' ) ); ?>
            <?php echo $form->error($model_locationCoordinates, 'longitude_degrees'); ?>
            </td>
            <td>
            <?php echo $form->labelEx($model_locationCoordinates, 'exactness'); ?>
            <?php echo $form->textField($model_locationCoordinates, 'exactness', array( 'size' => 5 )); ?>
            <?php echo $form->error($model_locationCoordinates, 'exactness'); ?>
            </td>
        </tr>
    </table>
</div>

<div class="row">
    <?php echo $form->labelEx($model_acquisitionEvent, 'annotation'); ?>
    <?php echo $form->textArea($model_acquisitionEvent, 'annotation', array( 'style' => 'width: 100%;' ) ); ?>
</div>
