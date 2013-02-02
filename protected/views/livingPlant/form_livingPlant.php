<div class="row">
    <?php echo $form->labelEx($model_botanicalObject, 'organisation_id'); ?>
    <?php echo CHtml::textField('BotanicalObject_organisation_name', $model_botanicalObject->organisation->description, array('readonly' => 'readonly')); ?>
    <?php echo $form->hiddenField($model_botanicalObject, 'organisation_id'); ?>
    <a href="#" onclick="$('#organisation_select_dialog').dialog('open'); return false;">Change</a>
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
            <td>
                <?php echo $form->labelEx($model_livingPlant, 'ipen_locked'); ?>
                <?php echo $form->checkBox($model_livingPlant, 'ipen_locked'); ?>
                <?php echo $form->error($model_livingPlant, 'ipen_locked'); ?>
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
    
    // Bind to change event of institution select
    $(document).ready(function(){
        // initialize jsTree for organisation
        $('#organisation_tree').jstree({
            "json_data": {
                "ajax": {
                    "url": "index.php?r=jSONOrganisation/japi&action=getChildren",
                    "data": function(n) {
                        var link = (n.children) ? n.children('a').first() : n;
                        var organisation_id = (link.attr) ? link.attr("data-organisation-id") : 0;
                        
                        return {
                            "organisation_id": organisation_id
                        };
                    }
                }
            },
            "plugins": ["json_data", "themes"]
        });
        
        // bind to click events onto tree items
        $('#organisation_tree a').live('click', function() {
            // update references to organisation
            $('#BotanicalObject_organisation_id').val( $(this).attr('data-organisation-id') );
            $('#BotanicalObject_organisation_name').val( $(this).text() );
            $('#organisation_select_dialog').dialog('close');
            
            // update IPEN code, only if not locked
            if( !$('#LivingPlant_ipen_locked').is(':checked') ) {
                $( "#LivingPlant_ipenNumberInstitutionCode" ).val( ipen_codes[$("#BotanicalObject_organisation_id").val()] );
            }
            return false;
        });
        
        // bind to new location event
        $('#locationName').bind('autocompleteselect', function(event, ui) {
            if( typeof ui.item.countryCode !== "undefined" && !$("#LivingPlant_ipen_locked").is(":checked") ) {
                $( "#LivingPlant_ipenNumberCountryCode" ).val( ui.item.countryCode );
            }
        });
    });
    
</script>

<div class="row">
    <?php
    // render certificates form
    require('form_certificates.php');
    ?>
</div>
<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'phyto_control'); ?>
    <?php echo $form->checkbox($model_livingPlant, 'phyto_control'); ?>
    <?php echo $form->error($model_livingPlant, 'phyto_control'); ?>
</div>

<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php echo $form->labelEx($model_livingPlant, 'index_seminum'); ?>
                <?php echo $form->checkBox($model_livingPlant, 'index_seminum'); ?>
                <?php echo $form->error($model_livingPlant, 'index_seminum'); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model_livingPlant, 'index_seminum_type_id'); ?>
                <?php echo $form->dropDownList($model_livingPlant, 'index_seminum_type_id', CHtml::listData(IndexSeminumType::model()->findAll(), 'id', 'type')); ?>
                <?php echo $form->error($model_livingPlant, 'index_seminum_type_id'); ?>
            </td>
        </tr>
    </table>
</div>

<div class="row">
    <?php echo $form->labelEx($model_livingPlant, 'culture_notes'); ?>
    <?php echo $form->textArea($model_livingPlant, 'culture_notes', array( 'style' => 'width: 100%;' )); ?>
    <?php echo $form->error($model_livingPlant, 'culture_notes'); ?>
</div>
