<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'living-plant-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'garden_site_id'); ?>
		<?php echo $form->dropDownList($model,'garden_site_id', CHtml::listData(GardenSite::model()->findAll(), 'id', 'description')); ?>
		<?php echo $form->error($model,'garden_site_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'accession_number'); ?>
		<?php echo $form->textField($model,'accession_number',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'accession_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ipen_number'); ?>
		<?php echo $form->textField($model,'ipen_number',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'ipen_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phyto_control'); ?>
		<?php echo $form->checkbox($model,'phyto_control'); ?>
		<?php echo $form->error($model,'phyto_control'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tree_record_id'); ?>
		<?php echo $form->textField($model,'tree_record_id'); ?>
		<?php echo $form->error($model,'tree_record_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phyto_sanitary_product_number'); ?>
		<?php echo $form->textField($model,'phyto_sanitary_product_number',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'phyto_sanitary_product_number'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->