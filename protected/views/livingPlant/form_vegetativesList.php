<?php echo CHtml::activeLabelEx(DerivativeVegetative::model(), 'derivative_vegetative_id'); ?>
<div class="row">
    <div class="vegetative_td">
        <?php echo CHtml::activeLabelEx(DerivativeVegetative::model(), 'cultivation_date'); ?>
    </div>
    <div class="vegetative_td">
        <?php echo CHtml::activeLabelEx(DerivativeVegetative::model(), 'accession_number'); ?>
    </div>
    <div class="vegetative_td">
        <?php echo CHtml::activeLabelEx(DerivativeVegetative::model(), 'organisation_id'); ?>
    </div>
</div>
<?php
foreach ($model_livingPlant->derivativeVegetatives as $model_derivativeVegetative) {
    require('form_vegetative.php');
}
