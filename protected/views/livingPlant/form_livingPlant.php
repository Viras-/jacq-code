<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'organisation_id'); ?>
    <?php echo CHtml::textField('BotanicalObject_organisation_name', $model_botanicalObject->organisation->description, array('readonly' => 'readonly')); ?>
    <?php echo $form->hiddenField($model_botanicalObject, 'organisation_id'); ?>
    <a href="#" onclick="$('#organisation_select_dialog').dialog('open'); return false;">Change</a>
</div>

<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'place_number'); ?>
    <?php echo $form->textField($model_livingPlant, 'place_number'); ?>
    <?php echo $form->error($model_livingPlant, 'place_number'); ?>
</div>

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

<div class="row">
    <?php
    // render certificates form
    require('form_certificates.php');
    ?>
</div>
<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'phyto_control'); ?>
    <?php echo $form->checkbox($model_livingPlant, 'phyto_control'); ?>
    <?php echo $form->error($model_livingPlant, 'phyto_control'); ?>
</div>

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

<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'cultivation_date'); ?>
    <?php
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'LivingPlant[cultivation_date]',
        // additional javascript options for the date picker plugin
        'options' => array(
            'showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true
        ),
        'htmlOptions' => array(

        ),
        'value' => $model_livingPlant->cultivation_date,
    ));
    ?>
    <?php echo $form->error($model_livingPlant, 'cultivation_date'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'culture_notes'); ?>
    <?php echo $form->textArea($model_livingPlant, 'culture_notes', array( 'style' => 'width: 100%;' )); ?>
    <?php echo $form->error($model_livingPlant, 'culture_notes'); ?>
</div>
