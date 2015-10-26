<?php echo CHtml::activeTextField($model_livingPlant, 'ipenNumberCountryCode', array('size' => 2, 'maxlength' => 2, 'readonly' => 'readonly')); ?>
-
<?php echo CHtml::activeDropDownList($model_livingPlant, 'ipenNumberState', array('X' => 'X', '0' => '0', '1' => '1')); ?>
-
<?php echo CHtml::activeTextField($model_livingPlant, 'ipenNumberInstitutionCode', array('size' => 2, 'maxlength' => 2, 'readonly' => 'readonly')); ?>
