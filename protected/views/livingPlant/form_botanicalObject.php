<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'taxon_id'); ?>
    <?php
    // Enable auto-completer for taxon field
    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
        'name' => 'scientificName',
        'sourceUrl' => 'index.php?r=autoComplete/taxon',
        // additional javascript options for the autocomplete plugin
        'options' => array(
            'minLength' => '2',
            'change' => 'js:function( event, ui ) {
                    if( typeof ui.item !== "undefined" ) {
                        $( "#BotanicalObject_taxon_id" ).val( ui.item.id );
                    }
                }',
        ),
        'value' => $model_botanicalObject->scientificName,
            /* 'htmlOptions' => array(
              'value' => $model_botanicalObject->getScientificName()
              ), */
    ));
    ?>
    <?php echo $form->hiddenField($model_botanicalObject, 'taxon_id'); ?>
    <?php echo $form->error($model_botanicalObject, 'taxon_id'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'phenology_id'); ?>
    <?php echo $form->dropDownList($model_botanicalObject, 'phenology_id', CHtml::listData(Phenology::model()->findAll(), 'id', 'phenology')); ?>
    <?php echo $form->error($model_botanicalObject, 'phenology_id'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'determined_by_id'); ?>
    <?php
    // Enable auto-completer for determined by field
    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
        'name' => 'determinedByName',
        'sourceUrl' => 'index.php?r=autoComplete/person',
        // additional javascript options for the autocomplete plugin
        'options' => array(
            'minLength' => '2',
            'select' => 'js:function( event, ui ) {
                    if( typeof ui.item !== "undefined" ) {
                        $( "#BotanicalObject_determined_by_id" ).val( ui.item.id );
                    }
                }',
        ),
        'value' => ($model_botanicalObject->determinedBy != null) ? $model_botanicalObject->determinedBy->name : '',
        'htmlOptions' => array(
            'onkeypress' => '$( "#BotanicalObject_determined_by_id" ).val("");'
        ),
    ));
    ?>
    <?php echo $form->hiddenField($model_botanicalObject, 'determined_by_id'); ?>
    <?php echo $form->error($model_botanicalObject, 'determined_by_id'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'habitat'); ?>
    <?php echo $form->textField($model_botanicalObject, 'habitat'); ?>
    <?php echo $form->error($model_botanicalObject, 'habitat'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'habitus'); ?>
    <?php echo $form->textField($model_botanicalObject, 'habitus'); ?>
    <?php echo $form->error($model_botanicalObject, 'habitus'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'annotation'); ?>
    <?php echo $form->textField($model_botanicalObject, 'annotation'); ?>
    <?php echo $form->error($model_botanicalObject, 'annotation'); ?>
</div>
