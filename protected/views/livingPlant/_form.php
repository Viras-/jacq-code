<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'living-plant-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model_livingPlant,$model_botanicalObject); ?>
        
        <?php
        require('form_acquisitionDate.php');
        require('form_acquisitionEvent.php');
        require('form_botanicalObject.php');
        require('form_livingPlant.php');
        ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model_livingPlant->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->