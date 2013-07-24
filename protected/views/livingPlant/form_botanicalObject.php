<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'phenology_id'); ?>
    <?php echo $form->dropDownList($model_botanicalObject, 'phenology_id', CHtml::listData(Phenology::model()->findAll(), 'id', 'phenology')); ?>
    <?php echo $form->error($model_botanicalObject, 'phenology_id'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'habitat'); ?>
    <?php echo $form->textField($model_botanicalObject, 'habitat'); ?>
    <?php echo $form->error($model_botanicalObject, 'habitat'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'habitus'); ?>
    <?php echo $form->textField($model_botanicalObject, 'habitus'); ?>
    <?php echo $form->error($model_botanicalObject, 'habitus'); ?>
</div>
