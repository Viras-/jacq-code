<div class="form">
    <?php
    // form for entering vegetative derivative data
    $form = $this->beginWidget('CActiveForm', array(
        'action' => $this->createUrl('livingPlant/ajaxVegetative', array(
            'derivative_vegetative_id' => $model_derivativeVegetative->derivative_vegetative_id,
            'living_plant_id' => $model_derivativeVegetative->living_plant_id
        )),
        'id' => 'vegetative-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'data-plus-as-tab' => "true"
        )
    ));

    // form element for id
    echo $form->hiddenField($model_derivativeVegetative, 'derivative_vegetative_id');
    ?>
    <!-- accession number -->
    <div class="row">
        <?php echo $form->labelEx($model_derivativeVegetative, 'accession_number'); ?>
        <?php echo Html::encode($model_livingPlant->accessionNumber); ?>
        -
        <?php echo $form->textField($model_derivativeVegetative, 'accession_number'); ?>
        <?php echo $form->error($model_derivativeVegetative, 'accession_number'); ?>
    </div>
    <!-- organization -->
    <div class="row">
        <?php echo $form->labelEx($model_derivativeVegetative, 'organisation_id'); ?>
        <?php echo CHtml::textField('DerivativeVegetative_organisation_name', ($model_derivativeVegetative->organisation != null) ? $model_derivativeVegetative->organisation->description : '', array('readonly' => 'readonly')); ?>
        <?php echo $form->hiddenField($model_derivativeVegetative, 'organisation_id'); ?>
        <a href="#" onclick="organisationDialogOpen('#DerivativeVegetative_organisation_id', '#DerivativeVegetative_organisation_name'); return false;">
            <img src="images/magnifier.png" >
        </a>
        <?php echo $form->error($model_derivativeVegetative, 'organisation_id'); ?>
    </div>
    <!-- place number -->
    <div class="row">
        <?php echo $form->labelEx($model_derivativeVegetative, 'place_number'); ?>
        <?php echo $form->textField($model_derivativeVegetative, 'place_number'); ?>
        <?php echo $form->error($model_derivativeVegetative, 'place_number'); ?>
    </div>
    <!-- phenology -->
    <div class="row">
        <?php echo $form->labelEx($model_derivativeVegetative, 'phenology_id'); ?>
        <?php echo $form->dropDownList($model_derivativeVegetative, 'phenology_id', Html::listDataSorted(Phenology::model()->findAll(), 'id', 'phenologyTranslated')); ?>
        <?php echo $form->error($model_derivativeVegetative, 'phenology_id'); ?>
    </div>
    <!-- cultivation date -->
    <div class="row">
        <?php echo $form->labelEx($model_derivativeVegetative, 'cultivation_date'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'DerivativeVegetative[cultivation_date]',
            // additional javascript options for the date picker plugin
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'changeMonth' => true,
                'changeYear' => true
            ),
            'htmlOptions' => array(
            ),
            'value' => $model_derivativeVegetative->cultivation_date,
        ));
        ?>
        <?php echo $form->error($model_derivativeVegetative, 'cultivation_date'); ?>
    </div>
    <!-- annotation -->
    <div class="row">
        <?php echo $form->labelEx($model_derivativeVegetative, 'index_seminum'); ?>
        <?php echo $form->checkBox($model_derivativeVegetative, 'index_seminum'); ?>
        <?php echo $form->error($model_derivativeVegetative, 'index_seminum'); ?>
    </div>

    <!-- separations -->
    <div class="row">
        <?php require('form_vegetativeSeparations.php'); ?>
    </div>

    <!-- annotation -->
    <div class="row">
        <?php echo $form->labelEx($model_derivativeVegetative, 'annotation'); ?>
        <?php echo $form->textArea($model_derivativeVegetative, 'annotation', array('style' => 'width: 100%;')); ?>
        <?php echo $form->error($model_derivativeVegetative, 'annotation'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>