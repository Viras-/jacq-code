<div class="row">
    <?php echo $form->labelEx($model_acquisitionEvent, 'acquisition_type_id'); ?>
    <?php echo $form->dropDownList($model_acquisitionEvent, 'acquisition_type_id', CHtml::listData(AcquisitionType::model()->findAll(), 'id', 'type')); ?>
    <?php echo $form->error($model_acquisitionEvent, 'acquisition_type_id'); ?>
</div>

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
                        
                        if( typeof ui.item.countryCode !== "undefined" ) {
                            $( "#LivingPlant_ipenNumberCountryCode" ).val( ui.item.countryCode );
                        }
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
    <?php echo $form->labelEx($model_acquisitionEvent, 'agent_id'); ?>
    <?php
    // Add text-input for each collector
    foreach( $model_acquisitionEvent->tblPeople as $index => $model_person ) {
        // Enable auto-completer for person field
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'name' => 'AcquisitionEvent_personName_' . $index,
            'sourceUrl' => 'index.php?r=autoComplete/person',
            // additional javascript options for the autocomplete plugin
            'options' => array(
                'minLength' => '2',
                'select' => 'js:function( event, ui ) {
                        if( typeof ui.item !== "undefined" ) {
                            $( "#AcquisitionEvent_tblPeople_' . $index . '" ).val( ui.item.id );
                        }
                    }',
            ),
            'value' => $model_person->name,
            'htmlOptions' => array(
                'onkeypress' => '$( "#AcquisitionEvent_tblPeople_' . $index . '" ).val("");'
            ),
        ));
        
        echo $form->hiddenField($model_person, 'id', array( 'id' => 'AcquisitionEvent_tblPeople_' .$index, 'name' => 'AcquisitionEvent[tblPeople][]' ) );
    }
    ?>
    <?php
    // Add addition field for new collector
    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
        'name' => 'AcqusitionEvent_personName',
        'sourceUrl' => 'index.php?r=autoComplete/person',
        // additional javascript options for the autocomplete plugin
        'options' => array(
            'minLength' => '2',
            'select' => 'js:function( event, ui ) {
                    if( typeof ui.item !== "undefined" ) {
                        $( "#AcqusitionEvent_person_id" ).val( ui.item.id );
                    }
                }',
        ),
        'value' => '',
        'htmlOptions' => array(
            'onkeypress' => '$( "#AcqusitionEvent_person_id" ).val("");'
        ),
    ));
    ?>
    <?php echo CHtml::hiddenField('AcqusitionEvent_person_id'); ?>
    <?php echo $form->error($model_acquisitionEvent, 'tblPeople'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_acquisitionEvent, 'number'); ?>
    <?php echo $form->textField($model_acquisitionEvent, 'number'); ?>
    <?php echo $form->error($model_acquisitionEvent, 'number'); ?>
</div>
