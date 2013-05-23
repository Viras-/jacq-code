<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'living-plant-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'data-plus-as-tab' => "true"
        )
            ));
    
    echo $form->hiddenField($model_botanicalObject, 'id');
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>
    
    <?php
    if( !$model_botanicalObject->isNewRecord ) {
    ?>
    <div style="text-align: right;">
        <a href="#"><img src="images/user.png" border="0" onclick="$('#authorization_management_dialog').dialog('open'); return false;" /></a>
    </div>
    <?php
    }
    ?>

    <?php //echo $form->errorSummary($model_acquisitionDate, $model_acquisitionEvent, $model_livingPlant,$model_botanicalObject);  ?>

    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Recording'); ?></legend>
        <div class="row">
            <?php echo $form->labelEx($model_botanicalObject, 'recording_date'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'BotanicalObject[recording_date]',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd',
                        'changeMonth' => true,
                        'changeYear' => true,
                    ),
                    'htmlOptions' => array(
                    ),
                    'value' => $model_botanicalObject->recording_date,
                ));
                ?>
            <?php echo $form->error($model_botanicalObject, 'recording_date'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model_livingPlant, 'incoming_date'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'IncomingDate[date]',
                // additional javascript options for the date picker plugin
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth' => true,
                    'changeYear' => true
                ),
                'htmlOptions' => array(

                ),
                'value' => $model_incomingDate->date,
            ));
            ?>
            <?php echo $form->error($model_incomingDate, 'date'); ?>
        </div>
    </fieldset>
    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Acquisition'); ?></legend>
        <?php
        require('form_acquisitionDate.php');
        ?>
        <hr/>
        <?php
        require('form_acquisitionEvent.php');
        ?>
    </fieldset>
    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Separation'); ?></legend>
        <?php
        require('form_separation.php');
        ?>
    </fieldset>
    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Living Plant'); ?></legend>
        <?php
        require('form_botanicalObject.php');
        require('form_livingPlant.php');
        ?>
    </fieldset>
    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Tree Record File'); ?></legend>
        <?php
        require('form_treeRecord.php');
        ?>
    </fieldset>
    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Relevancy Type'); ?></legend>
        <?php
        require('form_relevancy.php');
        ?>
    </fieldset>
    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Sex'); ?></legend>
        <?php
        require('form_sex.php');
        ?>
    </fieldset>

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
        // close the calling dialog
        $(this).dialog('close');
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
