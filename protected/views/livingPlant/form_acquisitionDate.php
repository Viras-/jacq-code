	<div class="row">
		<?php echo $form->labelEx($model_acquisitionDate,'year'); ?>
		<?php echo $form->textField($model_acquisitionDate,'year'); ?>
		<?php echo $form->error($model_acquisitionDate,'year'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_acquisitionDate,'month'); ?>
		<?php echo $form->textField($model_acquisitionDate,'month'); ?>
		<?php echo $form->error($model_acquisitionDate,'month'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_acquisitionDate,'day'); ?>
		<?php echo $form->textField($model_acquisitionDate,'day'); ?>
		<?php echo $form->error($model_acquisitionDate,'day'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_acquisitionDate,'custom'); ?>
		<?php echo $form->textField($model_acquisitionDate,'custom'); ?>
		<?php echo $form->error($model_acquisitionDate,'custom'); ?>
	</div>
