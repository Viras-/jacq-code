	<div class="row">
		<?php echo $form->labelEx($model_separation,'separation_type_id'); ?>
		<?php echo $form->dropDownList($model_separation,'separation_type_id', CHtml::listData(SeparationType::model()->findAll(), 'id', 'type')); ?>
		<?php echo $form->error($model_separation,'separation_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_separation,'date'); ?>
		<?php echo $form->textField($model_separation,'date'); ?>
		<?php echo $form->error($model_separation,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_separation,'annotation'); ?>
		<?php echo $form->textField($model_separation,'annotation'); ?>
		<?php echo $form->error($model_separation,'annotation'); ?>
	</div>
