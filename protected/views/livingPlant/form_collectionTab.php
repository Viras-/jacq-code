<!-- redetermine -->
<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'redetermine'); ?>
    <?php echo $form->checkBox($model_botanicalObject, 'redetermine' ); ?>
    <?php echo $form->error($model_botanicalObject, 'redetermine'); ?>
</div>
<!-- determination date -->
<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'determination_date'); ?>
    <?php
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'BotanicalObject[determination_date]',
        // additional javascript options for the date picker plugin
        'options' => array(
            'showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true
        ),
        'htmlOptions' => array(

        ),
        'value' => $model_botanicalObject->determination_date,
    ));
    ?>
    <?php echo $form->error($model_botanicalObject, 'determination_date'); ?>
</div>
<!-- determination type & name -->
<div class="row">
    <table>
        <tr>
            <td>
                <?php echo $form->labelEx($model_botanicalObject, 'ident_status_id'); ?>
                <?php echo $form->dropDownList($model_botanicalObject, 'ident_status_id', CHtml::listData(IdentStatus::model()->findAll(array('order'=>'status')), 'ident_status_id', 'status'), array('empty' => '')); ?>
                <?php echo $form->error($model_botanicalObject, 'ident_status_id'); ?>
            </td>
            <td>
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
            </td>
        </tr>
    </table>
</div>
<!-- relevancy -->
<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php echo $form->labelEx(Relevancy::model(), 'relevancy_type_id'); ?>
                <?php
                // Find all checked relevancy types for this entry
                $selected_relevancyTypes = array();
                if (!$model_livingPlant->isNewRecord) {
                    $models_relevancy = Relevancy::model()->findAll('living_plant_id=:living_plant_id', array(':living_plant_id' => $model_livingPlant->id));

                    // Add all selected relevancy types to array
                    foreach ($models_relevancy as $model_relevancy) {
                        $selected_relevancyTypes[] = $model_relevancy->relevancy_type_id;
                    }
                }

                // Create checkbox list for all relevancy type entries
                $models_relevancyType = RelevancyType::model()->findAll();
                echo CHtml::checkBoxList(
                        'RelevancyType', 
                        $selected_relevancyTypes, 
                        Html::listDataSorted(
                                $models_relevancyType,
                                'id',
                                'typeTranslated'
                        ), 
                        array(
                            'labelOptions' => array(
                                'style' => 'display: inline'
                            )
                        )
                );
                ?>
            </td>
        </tr>
    </table>
</div>
<!-- certificate(s) -->
<div class="row">
    <?php
    // render certificates form
    require('form_certificates.php');
    ?>
</div>
<!-- phyto control -->
<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'phyto_control'); ?>
    <?php echo $form->checkbox($model_livingPlant, 'phyto_control'); ?>
    <?php echo $form->error($model_livingPlant, 'phyto_control'); ?>
</div>
