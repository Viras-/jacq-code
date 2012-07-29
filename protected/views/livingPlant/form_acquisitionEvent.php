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
        'sourceUrl' => 'index.php?r=autoComplete/location',
        // additional javascript options for the autocomplete plugin
        'options' => array(
            'minLength' => '2',
            'change' => 'js:function( event, ui ) {
                    if( typeof ui.item !== "undefined" ) {
                        $( "#AcquisitionEvent_location_id" ).val( ui.item.id );
                    }
                }',
            'open' => 'js:function(event, ui) {
                    var liItem = $("<li class=\'ui-menu-item\' role=\'menuitem\'></li>")
                    var aItem = $( "<a class=\'ui-corner-all\'>More...</a>" );
                    aItem.hover( function() { $(this).addClass( "ui-state-hover" ); }, function() { $(this).removeClass( "ui-state-hover" ); } );
                    aItem.click( function(event, ui) { $( "#locationName" ).autocomplete( "option", "source", "index.php?r=autoComplete/location&geonames=true" ); return false; } );

                    liItem.append( aItem );
                    $(this).autocomplete( "widget" ).append( liItem );
                }'
        ),
        'value' => $model_acquisitionEvent->getLocationName()
        /*'htmlOptions' => array(
            'value' => $model_botanicalObject->getScientificName()
        ),*/
    ));
    ?>
    <?php echo $form->hiddenField($model_acquisitionEvent, 'location_id'); ?>
    <?php echo $form->error($model_acquisitionEvent, 'location_id'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_acquisitionEvent, 'agent_id'); ?>
    <?php
    // Enable auto-completer for taxon field
    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
        'name' => 'agentName',
        'sourceUrl' => 'index.php?r=autoComplete/person',
        // additional javascript options for the autocomplete plugin
        'options' => array(
            'minLength' => '2',
            'change' => 'js:function( event, ui ) {
                    if( typeof ui.item !== "undefined" ) {
                        $( "#AcquisitionEvent_agent_id" ).val( ui.item.id );
                    }
                }',
        ),
        'value' => $model_acquisitionEvent->getAgentName()
        /*'htmlOptions' => array(
            'value' => $model_botanicalObject->getScientificName()
        ),*/
    ));
    ?>
    <?php echo $form->hiddenField($model_acquisitionEvent, 'agent_id'); ?>
    <?php echo $form->error($model_acquisitionEvent, 'agent_id'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_acquisitionEvent, 'number'); ?>
    <?php echo $form->textField($model_acquisitionEvent, 'number'); ?>
    <?php echo $form->error($model_acquisitionEvent, 'number'); ?>
</div>
