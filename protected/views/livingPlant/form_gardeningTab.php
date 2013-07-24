<!-- recording date -->
<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'recording_date'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'BotanicalObject[recording_date]',
            // additional javascript options for the date picker plugin
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'changeMonth' => true,
                'changeYear' => true,
            ),
            'htmlOptions' => array(
            ),
            'value' => $model_botanicalObject->recording_date,
        ));
        ?>
    <?php echo $form->error($model_botanicalObject, 'recording_date'); ?>
</div>
<!-- incoming date -->
<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'incoming_date'); ?>
    <?php
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'IncomingDate[date]',
        // additional javascript options for the date picker plugin
        'options' => array(
            'showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true
        ),
        'htmlOptions' => array(

        ),
        'value' => $model_incomingDate->date,
    ));
    ?>
    <?php echo $form->error($model_incomingDate, 'date'); ?>
</div>
<!-- cultivation date -->
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
<!-- culture notes -->
<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'culture_notes'); ?>
    <?php echo $form->textArea($model_livingPlant, 'culture_notes', array( 'style' => 'width: 100%;' )); ?>
    <?php echo $form->error($model_livingPlant, 'culture_notes'); ?>
</div>
<!-- tree record(s) -->
<?php require('form_treeRecord.php'); ?>