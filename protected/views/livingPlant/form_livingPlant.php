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
