<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'scientificName_search'); ?>
		<?php echo $form->textField($model,'scientificName_search'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'organisation_search'); ?>
		<?php echo $form->textField($model,'organisation_search'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'accessionNumber_search'); ?>
		<?php echo $form->textField($model,'accessionNumber_search'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'place_number'); ?>
		<?php echo $form->textField($model,'place_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'location_search'); ?>
		<?php echo $form->textField($model,'location_search'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'separated_search'); ?>
		<?php echo $form->checkBox($model,'separated_search'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'index_seminum'); ?>
		<?php echo $form->checkBox($model,'index_seminum'); ?>
	</div>

        <!-- searching for label printing -->
        <div class="row">
                <?php echo $form->labelEx(LabelType::model(), 'label_type_id'); ?>
            <br/>
                <?php
                // display checkbox for label types
                echo CHtml::checkBoxList(
                        'LivingPlant[label_type_search]',
                        CHtml::listData($model, 'label_type_search', 'label_type_search'),
                        Html::listDataSorted(
                                LabelType::model()->findAll(),
                                'label_type_id', 
                                'typeTranslated'
                        ),
                        array(
                            'separator' => '<br/>'
                        )
                );
                ?>
        </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->