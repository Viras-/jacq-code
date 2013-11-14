<table>
    <tr>
        <td>
            <!-- scientific name -->
            <div class="row">
                <?php echo $form->labelEx($model_botanicalObject, 'scientific_name_id'); ?>
                <?php
                // Enable auto-completer for taxon field
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'scientificName',
                    'sourceUrl' => 'index.php?r=autoComplete/scientificName',
                    // additional javascript options for the autocomplete plugin
                    'options' => array(
                        'minLength' => '2',
                        'change' => 'js:function( event, ui ) {
                                if( typeof ui.item !== "undefined" ) {
                                    $( "#BotanicalObject_scientific_name_id" ).val( ui.item.id );
                                }
                            }',
                    ),
                    'value' => $model_botanicalObject->scientificName,
                        /* 'htmlOptions' => array(
                          'value' => $model_botanicalObject->getScientificName()
                          ), */
                ));
                ?>
                <?php echo $form->hiddenField($model_botanicalObject, 'scientific_name_id'); ?>
                <?php echo $form->error($model_botanicalObject, 'scientific_name_id'); ?>
            </div>
        </td>
        <td>
            <!-- family name -->
            <div class="row">
                <?php echo $form->labelEx($model_botanicalObject, 'family'); ?>
                <?php echo $form->textField($model_botanicalObject, 'family', array('readonly' => true)); ?>
                <?php echo $form->error($model_botanicalObject, 'family'); ?>
            </div>
        </td>
    </tr>
</table>

<!-- organisation -->
<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'organisation_id'); ?>
    <?php echo CHtml::textField('BotanicalObject_organisation_name', $model_botanicalObject->organisation->description, array('readonly' => 'readonly')); ?>
    <?php echo $form->hiddenField($model_botanicalObject, 'organisation_id'); ?>
    <a href="#" onclick="$('#organisation_select_dialog').dialog('open'); return false;">Change</a>
</div>

<!-- place number -->
<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'place_number'); ?>
    <?php echo $form->textField($model_livingPlant, 'place_number'); ?>
    <?php echo $form->error($model_livingPlant, 'place_number'); ?>
</div>

<!-- IPEN & accession number -->
<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php require("form_ipenNumber.php"); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model_livingPlant, 'ipen_locked'); ?>
                <?php echo $form->checkBox($model_livingPlant, 'ipen_locked'); ?>
                <?php echo $form->error($model_livingPlant, 'ipen_locked'); ?>
            </td>
            <td>
                <?php require("form_accessionNumber.php"); ?>
            </td>
        </tr>
    </table>
</div>

<!-- display alternative accession numbers -->
<div class="row">
    <?php require("form_alternativeAccessionNumbers.php"); ?>
</div>

<!-- plant sex -->
<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php echo $form->labelEx(BotanicalObjectSex::model(), 'sex_id'); ?>
                <?php
                // display checkbox for assigned sexes
                echo CHtml::checkBoxList(
                        'Sex',
                        CHtml::listData($model_botanicalObject->botanicalObjectSexes, 'id', 'id'),
                        CHtml::listData(
                                Sex::model()->findAll(),
                                'id', 
                                'sexTranslated'
                        ),
                        array(
                            'labelOptions' => array('style' => 'display: inline'),
                            'separator' => ''
                        )
                );
                ?>
            </td>
        </tr>
    </table>
</div>

<!-- marking for label printing -->
<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php echo $form->labelEx(LabelType::model(), 'label_type_id'); ?>
                <?php
                // display checkbox for label types
                echo CHtml::checkBoxList(
                        'LabelTypes',
                        CHtml::listData($model_botanicalObject->tblLabelTypes, 'label_type_id', 'label_type_id'),
                        CHtml::listData(
                                LabelType::model()->findAll(),
                                'label_type_id', 
                                'typeTranslated'
                        ),
                        array(
                            'labelOptions' => array('style' => 'display: inline'),
                            'separator' => ''
                        )
                );
                ?>
            </td>
        </tr>
    </table>
</div>

<!-- separated (not in collection anymore) -->
<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'separated'); ?>
    <?php echo $form->checkBox($model_botanicalObject, 'separated'); ?>
    <?php echo $form->error($model_botanicalObject, 'separated'); ?>
</div>

<!-- separations -->
<div class="row">
    <table style="width: auto;">
        <?php
        $model_separations = $model_botanicalObject->separations;
        $model_separations[] = new Separation;  // Add one new entry for adding
        
        foreach( $model_separations as $i => $model_separation ) {
        ?>
        <tr>
            <td>
                <?php
                $separation_types = CHtml::listData(SeparationType::model()->findAll(), 'id', 'typeTranslated');
                
                // check if we have a valid id already, if not skip the hidden field
                if( $model_separation->id > 0 ) {
                    echo $form->hiddenField($model_separation, "[$i]id");
                }
                else {
                    $separation_types = array( '' => Yii::t('jacq_types', 'none') ) + $separation_types;
                }
                
                echo $form->labelEx($model_separation, 'separation_type_id');
                echo $form->dropDownList($model_separation, "[$i]separation_type_id", $separation_types );
                echo $form->error($model_separation, 'separation_type_id');
                ?>
            </td>
            <td>
                <?php
                echo $form->labelEx($model_separation, 'date');
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => "Separation[$i][date]",
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
                echo $form->error($model_separation, 'date');
                ?>
            </td>
            <td>
                <?php
                echo $form->labelEx($model_separation, 'annotation');
                echo $form->textField($model_separation, "[$i]annotation");
                echo $form->error($model_separation, 'annotation');
                ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>
