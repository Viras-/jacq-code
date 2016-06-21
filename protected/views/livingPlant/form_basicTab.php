<!-- scientific name -->
<div class="row">
    <table width="100%">
        <tr>
            <td>
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

                                    // update scientific name of toolbar as well
                                    $(".toolbar .scientific_name").text( ui.item.value );
                                }
                            }',
                    ),
                    'value' => $model_botanicalObject->scientificName,
                    'htmlOptions' => array(
                        'style' => 'width: 80%;'
                    ),
                ));
                ?>
                <a href="#" onclick="$('#scientific_name_information_dialog').dialog('open');
                        return false;"><img src="images/page_white_edit.png" ></a>
                   <?php echo $form->hiddenField($model_botanicalObject, 'scientific_name_id'); ?>
                   <?php echo $form->error($model_botanicalObject, 'scientific_name_id'); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model_livingPlant, 'cultivar_id'); ?>
                <?php echo $form->dropDownList($model_livingPlant, 'cultivar_id', Html::listDataSorted(Cultivar::model()->findAllByAttributes(array('scientific_name_id' => $model_botanicalObject->scientific_name_id)), 'cultivar_id', 'cultivar', true)); ?>
                <?php echo $form->error($model_livingPlant, 'cultivar_id'); ?>
            </td>
        </tr>
    </table>
</div>
<!-- family name -->
<div class="row">
    <table border="0" width="100%">
        <tr>
            <td>
                <?php echo $form->labelEx($model_botanicalObject, 'family'); ?>
                <?php echo $form->textField($model_botanicalObject, 'family', array('readonly' => true)); ?>
                <?php echo $form->error($model_botanicalObject, 'family'); ?>
            </td>
            <td width='40%'>
                <?php echo $form->labelEx($model_botanicalObject, 'familyReference'); ?>
                <?php echo CHtml::encode($model_botanicalObject->familyReference); ?>
            </td>
        </tr>
    </table>
</div>

<div class="row">
    <table border="0" width="100%" style="margin: 0;">
        <tr>
            <td>
                <table border="0" width="100%" style="margin: 0;">
                    <tr>
                        <td>
                            <!-- organisation -->
                            <?php echo $form->labelEx($model_botanicalObject, 'organisation_id'); ?>
                            <?php echo CHtml::textField('BotanicalObject_organisation_name', $model_botanicalObject->organisation->description, array('readonly' => 'readonly')); ?>
                            <?php echo $form->hiddenField($model_botanicalObject, 'organisation_id'); ?>
                            <a href="#" onclick="$('#organisation_select_dialog').dialog('open');
                                    return false;"><img src="images/magnifier.png" ></a>
                        </td>
                        <td>
                            <!-- reviewed -->
                            <?php echo $form->labelEx($model_livingPlant, 'reviewed'); ?>
                            <?php echo $form->checkBox($model_livingPlant, 'reviewed'); ?>
                            <?php echo $form->error($model_livingPlant, 'reviewed'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <!-- place number -->
                            <?php echo $form->labelEx($model_livingPlant, 'place_number'); ?>
                            <?php echo $form->textField($model_livingPlant, 'place_number'); ?>
                            <?php echo $form->error($model_livingPlant, 'place_number'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <!-- accession number -->
                            <?php require("form_accessionNumber.php"); ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td width='40%'>
                <?php
                // render (important) relevancy form
                echo $this->renderPartial('form_relevancy', array('important' => 1, 'form' => $form, 'model_livingPlant' => $model_livingPlant));
                ?>
            </td>
        </tr>
    </table>

</div>

<!-- IPEN & accession number -->
<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'ipen_type'); ?>
    <?php
    echo $form->dropDownList(
            $model_livingPlant, 'ipen_type', array('default' => Yii::t('jacq', 'default'), 'custom' => Yii::t('jacq', 'custom'))
    );
    ?>
    <script type="text/javascript">
        $('#LivingPlant_ipen_type').on('change', function () {
            $.ajax({
                url: 'index.php?r=livingPlant/ajaxIpenNumber&id=<?php echo $model_livingPlant->id ?>&type=' + $(this).val()
            }).done(function (data) {
                $('#ipenNumber').html(data);
            });
        });
    </script>
</div>
<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php echo $form->labelEx($model_livingPlant, 'ipen_number'); ?>
                <?php
                if ($model_livingPlant->ipen_locked) {
                    ?>
                    <?php echo CHtml::encode($model_livingPlant->ipenNumber); ?>
                    <?php
                }
                else {
                    ?>
                    <span id="ipenNumber">
                        <?php
                        if ($model_livingPlant->ipen_type === 'custom') {
                            require("form_ipenNumberCustom.php");
                        }
                        else {
                            require("form_ipenNumberDefault.php");
                        }
                        ?>
                    </span>
                    <?php echo $form->error($model_livingPlant, 'ipen_number'); ?>
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $form->labelEx($model_livingPlant, 'ipen_locked'); ?>
                <?php echo $form->checkBox($model_livingPlant, 'ipen_locked'); ?>
                <?php echo $form->error($model_livingPlant, 'ipen_locked'); ?>
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
                        'Sex', CHtml::listData($model_botanicalObject->botanicalObjectSexes, 'sex_id', 'sex_id'), Html::listDataSorted(
                                Sex::model()->findAll(), 'id', 'sexTranslated'
                        ), array(
                    'labelOptions' => array('style' => 'display: inline'),
                    'separator' => ''
                        )
                );
                ?>
            </td>
        </tr>
    </table>
</div>

<?php
// access to label section only if either clearing or assigning is allowed for this user
if (Yii::app()->user->checkAccess('oprtn_assignLabelType') || Yii::app()->user->checkAccess('oprtn_clearLabelType')) {
    ?>
    <!-- marking for label printing -->
    <div class="row">
        <table style="width: 100%;">
            <tr>
                <td>
                    <?php echo $form->labelEx(LabelType::model(), 'label_type_id'); ?>
                </td>
            </tr>
            <?php
            // synonym editing only if user is allowed to do so
            if (Yii::app()->user->checkAccess('oprtn_assignLabelType')) {
                ?>
                <tr>
                    <td>
                        <?php echo $form->labelEx($model_livingPlant, 'labelSynonymScientificName'); ?>
                        <?php
                        // Enable auto-completer for taxon field
                        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                            'name' => 'label_synonymName',
                            'sourceUrl' => 'index.php?r=autoComplete/scientificName',
                            // additional javascript options for the autocomplete plugin
                            'options' => array(
                                'minLength' => '2',
                                'change' => 'js:function( event, ui ) {
                                if( typeof ui.item !== "undefined" ) {
                                    $( "#LivingPlant_label_synonym_scientific_name_id" ).val( ui.item.id );
                                }
                            }',
                            ),
                            'value' => $model_livingPlant->labelSynonymScientificName,
                            'htmlOptions' => array(
                                'style' => 'width: 80%;'
                            ),
                        ));
                        ?>
                        <?php echo $form->hiddenField($model_livingPlant, 'label_synonym_scientific_name_id'); ?>
                        <?php echo $form->error($model_livingPlant, 'label_synonym_scientific_name_id'); ?>
                    </td>
                </tr>
                <!-- display annotation field for labels, if user is allowed to assign label printing -->
                <tr>
                    <td>
                        <?php echo $form->labelEx($model_livingPlant, 'label_annotation'); ?>
                        <?php echo $form->textField($model_livingPlant, 'label_annotation', array('style' => 'width: 80%;')); ?>
                        <?php echo $form->error($model_livingPlant, 'label_annotation'); ?>
                    </td>
                </tr>
                <?php
            }

            // by default only display assigned labels
            $labelCheckBoxList_data = CHtml::listData($model_botanicalObject->tblLabelTypes, 'label_type_id', 'label_type_id');
            $labelCheckBoxList_select = Html::listDataSorted($model_botanicalObject->tblLabelTypes, 'label_type_id', 'typeTranslated');

            // if user is allowed to assign the labels, then it sees all available types
            if (Yii::app()->user->checkAccess('oprtn_assignLabelType')) {
                $labelCheckBoxList_select = Html::listDataSorted(LabelType::model()->findAll(), 'label_type_id', 'typeTranslated');
            }
            ?>
            <tr>
                <td>
                    <?php
                    // display checkbox for label types
                    echo CHtml::checkBoxList(
                            'LabelTypes', $labelCheckBoxList_data, $labelCheckBoxList_select, array(
                        'labelOptions' => array('style' => 'display: inline'),
                        'separator' => ''
                            )
                    );
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <?php
}
?>

<!-- separated (not in collection anymore) -->
<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'separated'); ?>
    <?php echo $form->checkBox($model_botanicalObject, 'separated'); ?>
    <?php echo $form->error($model_botanicalObject, 'separated'); ?>
</div>

<!-- separations -->
<div class="row">
    <?php require("form_separations.php"); ?>
</div>
