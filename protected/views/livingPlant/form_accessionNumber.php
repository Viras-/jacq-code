<?php echo $form->labelEx($model_livingPlant, 'accessionNumber'); ?>
<?php if ($model_livingPlant->isNewRecord) { ?>
    auto
    <?php
}
else {
    ?>
    <?php echo CHtml::encode($model_livingPlant->accessionNumber); ?>
<?php } ?>
