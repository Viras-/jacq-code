<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ipen_number'); ?>
		<?php echo $form->textField($model,'ipen_number',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phyto_control'); ?>
		<?php echo $form->textField($model,'phyto_control'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phyto_sanitary_product_number'); ?>
		<?php echo $form->textField($model,'phyto_sanitary_product_number',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->