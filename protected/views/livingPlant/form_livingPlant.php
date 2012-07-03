<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'garden_site_id'); ?>
    <?php echo $form->dropDownList($model_livingPlant, 'garden_site_id', CHtml::listData(GardenSite::model()->findAll(), 'id', 'description')); ?>
    <?php echo $form->error($model_livingPlant, 'garden_site_id'); ?>
</div>

<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php require("form_accessionNumber.php"); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model_livingPlant, 'ipen_number'); ?>
                <?php echo $form->textField($model_livingPlant, 'ipen_number', array('size' => 8, 'maxlength' => 20)); ?>
                <?php echo $form->error($model_livingPlant, 'ipen_number'); ?>
            </td>
        </tr>
    </table>
</div>

<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'phyto_control'); ?>
    <?php echo $form->checkbox($model_livingPlant, 'phyto_control'); ?>
    <?php echo $form->error($model_livingPlant, 'phyto_control'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'tree_record_id'); ?>
    <?php echo $form->textField($model_livingPlant, 'tree_record_id'); ?>
    <?php echo $form->error($model_livingPlant, 'tree_record_id'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'phyto_sanitary_product_number'); ?>
    <?php echo $form->textField($model_livingPlant, 'phyto_sanitary_product_number', array('size' => 20, 'maxlength' => 20)); ?>
    <?php echo $form->error($model_livingPlant, 'phyto_sanitary_product_number'); ?>
</div>
