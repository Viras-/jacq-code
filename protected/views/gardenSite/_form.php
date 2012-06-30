<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'garden-site-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'department'); ?>
		<?php echo $form->textField($model,'department',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'department'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'greenhouse'); ?>
		<?php echo $form->checkbox($model,'greenhouse'); ?>
		<?php echo $form->error($model,'greenhouse'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'parent_garden_site_id'); ?>
		<?php
                $garden_sites = CHtml::listData(GardenSite::model()->findAll(), 'id', 'description');
                $garden_sites = array( '' => 'None' ) + $garden_sites;
                echo $form->dropDownList($model,'parent_garden_site_id', $garden_sites);
                ?>
		<?php echo $form->error($model,'parent_garden_site_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gardener_id'); ?>
		<?php
                echo $form->dropDownList($model,'gardener_id', CHtml::listData(User::model()->findAll(), 'id', 'username'));
                ?>
		<?php echo $form->error($model,'gardener_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->