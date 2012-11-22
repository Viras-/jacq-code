    <?php if($model_livingPlant->isNewRecord) { ?>
    <?php echo $form->labelEx($model_livingPlant, 'accession_number_id'); ?>
    <?php echo $form->textField($model_accessionNumber, 'year', array( 'size' => 4 )); ?>
    -auto-
    <?php echo $form->textField($model_accessionNumber, 'individual', array( 'size' => 3 )); ?>
    <?php    
    }
    else {
    ?>
    <?php echo $form->labelEx($model_livingPlant, 'accession_number_id'); ?>
    <?php echo CHtml::encode($model_accessionNumber->accessionNumber); ?>
    <?php } ?>
