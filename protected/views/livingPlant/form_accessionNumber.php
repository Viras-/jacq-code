    <?php echo $form->labelEx($model_livingPlant, 'accession_number_id'); ?>
    <?php echo $form->textField($model_accessionNumber, 'year', array( 'size' => 4 )); ?>
    <?php
    if($model_accessionNumber->id) {
        printf( '%05d', $model_accessionNumber->id );
    }
    else {
    ?>
    -auto-
    <?php
    }
    ?>
    <?php echo $form->textField($model_accessionNumber, 'individual', array( 'size' => 3 )); ?>
