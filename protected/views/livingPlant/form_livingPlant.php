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
                <?php echo CHtml::textField('ipen_number_countryCode', 'XX', array('size' => 2, 'maxlength' => 2, 'readonly' => 'readonly')); ?>
                -
                <?php echo CHtml::dropDownList('ipen_number_state', 'X', array( 'X' => 'X', '0' => '0', '1' => '1' ) ); ?>
                -
                <?php echo CHtml::textField('ipen_number_institutionCode', 'WU', array('size' => 2, 'maxlength' => 2, 'readonly' => 'readonly')); ?>
                <?php echo $form->error($model_livingPlant, 'ipen_number'); ?>
            </td>
        </tr>
    </table>
</div>
<!-- List of source-codes for institutions to auto-fill the IPEN number -->
<script type="text/javascript">
    var source_codes = {
        <?php
        $meta_models = Meta::model()->findAll();
        foreach( $meta_models as $meta_model ) {
            echo "'" . $meta_model->source_id . "': '" . $meta_model->source_code . "',\n";
        }
        ?>
        '0': ''
    };
    
    /**
     * Called when the institution dropdown is changed
     */
    function source_id_change(event, ui) {
        $( "#ipen_number_institutionCode" ).val( source_codes[$("#BotanicalObject_source_id").val()] );
    }
    
    // Bind to change event of institution select
    $(document).ready(function(){
        $('#BotanicalObject_source_id').bind('change', source_id_change);
        
        // Update intital selection
        source_id_change();
    });
</script>


<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'phyto_control'); ?>
    <?php echo $form->checkbox($model_livingPlant, 'phyto_control'); ?>
    <?php echo $form->error($model_livingPlant, 'phyto_control'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'phyto_sanitary_product_number'); ?>
<?php echo $form->textField($model_livingPlant, 'phyto_sanitary_product_number', array('size' => 20, 'maxlength' => 20)); ?>
<?php echo $form->error($model_livingPlant, 'phyto_sanitary_product_number'); ?>
</div>
