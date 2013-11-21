<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'living-plant-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'data-plus-as-tab' => "true"
        )
            ));
    
    // pass reference to main form through sub-render calls
    $data['form'] = &$form;
    
    // maintain reference to botanical object id
    echo $form->hiddenField($model_botanicalObject, 'id');
    ?>

    <?php
    if( !$model_botanicalObject->isNewRecord ) {
    ?>
    <div style="text-align: right;">
        <a href="#"><img src="images/user.png" border="0" onclick="$('#authorization_management_dialog').dialog('open'); return false;" /></a>
    </div>
    <?php
    }
    ?>

    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Basic'); ?></legend>
        <?php $this->renderPartial('form_basicTab', $data); ?>
    </fieldset>

    <?php
    $this->widget('zii.widgets.jui.CJuiTabs',array(
        'tabs'=>array(
            Yii::t('jacq', 'Aquisition') => $this->renderPartial('form_acquisitionTab', $data, true),
            Yii::t('jacq', 'Gardening') => $this->renderPartial('form_gardeningTab', $data, true),
            Yii::t('jacq', 'Collection') => $this->renderPartial('form_collectionTab', $data, true),
            Yii::t('jacq', 'Derivatives') => 'Work in progress',
            Yii::t('jacq', 'Inventory') => 'Work in progress',
        ),
        // additional javascript options for the tabs plugin
        'options'=>array(
        ),
    ));
    ?>

    <?php //echo $form->errorSummary($model_acquisitionDate, $model_acquisitionEvent, $model_livingPlant,$model_botanicalObject);  ?>

    <br />
    <?php require('form_importProperties.php'); ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model_livingPlant->isNewRecord ? Yii::t('jacq', 'Create') : Yii::t('jacq', 'Save'), array('data-plus-as-tab' => "false")); ?>
    </div>
    <?php $this->endWidget(); ?>

    <!-- jdialog widgets -->
    <?php
    // Widget for opening & displaying the PDF pages
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'tree_record_view_dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Tree Record',
            'autoOpen' => false,
            'resizable' => false,
            'width' => 630,
        ),
    ));
    ?>
    <iframe id="tree_record_view_dialog_iframe" scrolling="no" src="about:blank" style="width: 600px; height: 500px;">No iFrame support in your browser</iframe>
    <?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- iframe for downloading tree records -->
    <iframe id="tree_record_download_iframe" scrolling="no" src="about:blank" style="display: none;">No iFrame support in your browser</iframe>
    
    <?php
    // widget for chosing the organisation
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'organisation_select_dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Organisation',
            'autoOpen' => false,
            'resizable' => false,
            'width' => 630,
        ),
    ));
    ?>
    <div id="organisation_tree" style="height: 400px;"></div>
    <?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>

    <?php
    // widget for authorization management
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'authorization_management_dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => Yii::t('jacq', 'Authorization'),
            'autoOpen' => false,
            'resizable' => false,
            'width' => 630,
            'buttons' => array(
                array(
                    'text' => Yii::t('jacq', 'Close'),
                    'click' => new CJavaScriptExpression("function() { $(this).dialog('close'); }")
                ),
                array(
                    'text' => Yii::t('jacq', 'Save'),
                    'click' => new CJavaScriptExpression('authorizationSave')
                ),
            ),
            'open' => new CJavaScriptExpression('authorizationOpen'),
            'close' => new CJavaScriptExpression('authorizationClose'),
        ),
    ));
    ?>
    <div id="authorization_view" style="height: 400px;"></div>
    <?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
</div><!-- form -->


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
     * Called when the authorization dialog is opened (for reloading)
     */
    function authorizationOpen(event,ui) {
        // load authorization view and assign it to div
        $('#authorization_view').load('<?php echo $this->createUrl('authorization/ajaxBotanicalObjectAccess', array('botanical_object_id' => $model_botanicalObject->id)); ?>');
    }
    
    /**
     * Called when the authorization dialog is closed (empty content)
     */
    function authorizationClose(event,ui) {
        $('#authorization_view').html('');
    }
    
    /**
     * Called when the authorization settings are saved
     */
    function authorizationSave(event,ui) {
        // keep reference to dialog
        var self = this;
        
        // get all select values for sending to the server
        var formData = {};
        $('#authorization_form select').each(function() {
            formData[$(this).attr('name')] = $(this).val();
        });
        
        // disable the whole form
        $('#authorization_form select').attr('disabled', 'disabled');
        
        // send the request to the server
        $.post(
                '<?php echo $this->createUrl('authorization/ajaxBotanicalObjectAccessSave', array('botanical_object_id' => $model_botanicalObject->id)); ?>',
                formData,
                function(data, textStatus, jqXHR) {
                    // close the calling dialog
                    $(self).dialog('close');
                }
        );
    }
    
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
