    <?php echo $form->labelEx($model_livingPlant, 'ipen_number'); ?>
<?php
if( $model_livingPlant->ipen_locked ) {
?>
    <?php echo CHtml::encode($model_livingPlant->ipen_number); ?>
<?php
}
else {
?>
    <?php echo $form->textField($model_livingPlant,'ipenNumberCountryCode', array('size' => 2, 'maxlength' => 2, 'readonly' => 'readonly')); ?>
    -
    <?php echo $form->dropDownList($model_livingPlant, 'ipenNumberState', array( 'X' => 'X', '0' => '0', '1' => '1' ) ); ?>
    -
    <?php echo $form->textField($model_livingPlant, 'ipenNumberInstitutionCode', array('size' => 2, 'maxlength' => 2, 'readonly' => 'readonly')); ?>
    <?php echo $form->error($model_livingPlant, 'ipen_number'); ?>
<?php
}
?>