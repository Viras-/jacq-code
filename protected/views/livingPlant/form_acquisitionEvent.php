	<div class="row">
		<?php echo $form->labelEx($model_acquisitionEvent,'acquisition_type_id'); ?>
		<?php echo $form->dropDownList($model_acquisitionEvent,'acquisition_type_id', CHtml::listData(AcquisitionType::model()->findAll(), 'id', 'type')); ?>
		<?php echo $form->error($model_acquisitionEvent,'acquisition_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_acquisitionEvent,'location_id'); ?>
		<?php echo $form->textField($model_acquisitionEvent,'location_id'); ?>
		<?php echo $form->error($model_acquisitionEvent,'location_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_acquisitionEvent,'agent_id'); ?>
		<?php echo $form->textField($model_acquisitionEvent,'agent_id'); ?>
		<?php echo $form->error($model_acquisitionEvent,'agent_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_acquisitionEvent,'number'); ?>
		<?php echo $form->textField($model_acquisitionEvent,'number'); ?>
		<?php echo $form->error($model_acquisitionEvent,'number'); ?>
	</div>
