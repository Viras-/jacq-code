<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'organisation_id'); ?>
    <?php echo $form->dropDownList($model_botanicalObject, 'organisation_id', CHtml::listData(Organisation::model()->findAll(), 'id', 'description')); ?>
    <?php echo $form->error($model_botanicalObject, 'organisation_id'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'place_number'); ?>
    <?php echo $form->textField($model_livingPlant, 'place_number'); ?>
    <?php echo $form->error($model_livingPlant, 'place_number'); ?>
</div>

<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php require("form_accessionNumber.php"); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model_livingPlant, 'ipen_number'); ?>
                <?php echo $form->textField($model_livingPlant,'ipenNumberCountryCode', array('size' => 2, 'maxlength' => 2, 'readonly' => 'readonly')); ?>
                -
                <?php echo $form->dropDownList($model_livingPlant, 'ipenNumberState', array( 'X' => 'X', '0' => '0', '1' => '1' ) ); ?>
                -
                <?php echo $form->textField($model_livingPlant, 'ipenNumberInstitutionCode', array('size' => 2, 'maxlength' => 2, 'readonly' => 'readonly')); ?>
                <?php echo $form->error($model_livingPlant, 'ipen_number'); ?>
            </td>
        </tr>
    </table>
</div>
<!-- List of source-codes for institutions to auto-fill the IPEN number -->
<script type="text/javascript">
    var ipen_codes = {
        <?php
        $ipen_code_models = Organisation::model()->findAll();
        foreach( $ipen_code_models as $ipen_code_model ) {
            echo "'" . $ipen_code_model->id . "': '" . $ipen_code_model->getIpenCode() . "',\n";
        }
        ?>
        '0': ''
    };
    
    /**
     * Called when the institution dropdown is changed
     */
    function source_id_change(event, ui) {
        $( "#LivingPlant_ipenNumberInstitutionCode" ).val( ipen_codes[$("#BotanicalObject_organisation_id").val()] );
    }
    
    // Bind to change event of institution select
    $(document).ready(function(){
        $('#BotanicalObject_organisation_id').bind('change', source_id_change);
        
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
