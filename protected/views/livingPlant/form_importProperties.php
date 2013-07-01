<?php
if( $model_botanicalObject->importProperties != NULL ) {
?>
    <fieldset>
        <legend><?php echo Yii::t('jacq', 'ImportProperties'); ?></legend>
        <div class="row">
            <?php echo $form->labelEx($model_botanicalObject->importProperties, 'IDPflanze'); ?>
            <?php echo $form->textField($model_botanicalObject->importProperties, 'IDPflanze', array('readonly' => 'readonly')); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model_botanicalObject->importProperties, 'species_name'); ?>
            <?php echo $form->textField($model_botanicalObject->importProperties, 'species_name', array('readonly' => 'readonly')); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model_botanicalObject->importProperties, 'Revier'); ?>
            <?php echo $form->textField($model_botanicalObject->importProperties, 'Revier', array('readonly' => 'readonly')); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model_botanicalObject->importProperties, 'Verbreitung'); ?>
            <?php echo $form->textField($model_botanicalObject->importProperties, 'Verbreitung', array('readonly' => 'readonly')); ?>
        </div>
    </fieldset>
<?php
}
