<?php
/* @var $this ScientificNameInformationController */
/* @var $model_scientificNameInformation ScientificNameInformation */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'scientific-name-information-update-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
        'enableAjaxValidation' => false,
    ));
    ?>
    <strong><?php echo $model_scientificNameInformation->viewTaxon->getScientificName(); ?></strong>

    <?php echo $form->errorSummary($model_scientificNameInformation); ?>

    <div class="row">
        <?php echo $form->labelEx($model_scientificNameInformation, 'habitus_type_id'); ?>
        <?php echo $form->dropDownList($model_scientificNameInformation, 'habitus_type_id', CHtml::listData(HabitusType::model()->findAll(), 'habitus_type_id', 'habitusTranslated', true)); ?>
        <?php echo $form->error($model_scientificNameInformation, 'habitus_type_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model_scientificNameInformation, 'spatial_distribution'); ?>
        <?php echo $form->textField($model_scientificNameInformation, 'spatial_distribution', array('style' => 'width: 100%;')); ?>
        <?php echo $form->error($model_scientificNameInformation, 'spatial_distribution'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model_scientificNameInformation, 'common_names'); ?>
        <?php echo $form->textField($model_scientificNameInformation, 'common_names', array('style' => 'width: 100%;')); ?>
        <?php echo $form->error($model_scientificNameInformation, 'common_names'); ?>
    </div>
    
    <div class="row">
        <?php require('form_cultivars.php'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->