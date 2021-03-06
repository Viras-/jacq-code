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

    <!-- fixed floating toolbar for easier saving, only in update mode available -->
    <?php
    if (!$model_livingPlant->isNewRecord) {
        ?>
        <div class="toolbar">
            <table>
                <tr>
                    <td>
                        <span class="scientific_name"><?php echo $model_botanicalObject->scientificName; ?></span> (<?php echo $model_livingPlant->accessionNumber; ?>)
                    </td>
                    <td class="right">
                        <?php
                        echo Html::button(
                                Yii::t('jacq', 'Copy & New'), array(
                            'type' => 'button',
                            'onclick' => 'window.location.href = "' . $this->createUrl('copyAndNew', array(
                                'living_plant_id' => $model_livingPlant->id
                                    )
                            ) . '"'
                                )
                        );
                        ?>
                        <?php echo CHtml::submitButton(Yii::t('jacq', 'Save'), array('data-plus-as-tab' => "false")); ?>
                    </td>
                </tr>
            </table>
        </div>

        <script type="text/javascript">
            $(window).bind('scroll', function () {
                if ($(window).scrollTop() > 60) {
                    $('.toolbar').addClass('fixed');
                    $('.toolbar').width($('#content').width());
                } else {
                    $('.toolbar').removeClass('fixed');
                }
            });
        </script>
        <?php
    }
    ?>


    <?php
    if (!$model_botanicalObject->isNewRecord && Yii::app()->user->checkAccess('oprtn_aclBotanicalObject')) {
        ?>
        <div style="text-align: right;">
            <a href="#"><img src="images/user.png" border="0" onclick="$('#authorization_management_dialog').dialog('open');
                    return false;" /></a>
        </div>
        <?php
    }
    ?>

    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Basic'); ?></legend>
        <?php $this->renderPartial('form_basicTab', $data); ?>
    </fieldset>

    <?php
    $this->widget('zii.widgets.jui.CJuiTabs', array(
        'tabs' => array(
            Yii::t('jacq', 'Aquisition') => $this->renderPartial('form_acquisitionTab', $data, true),
            Yii::t('jacq', 'Gardening') => $this->renderPartial('form_gardeningTab', $data, true),
            Yii::t('jacq', 'Collection') => $this->renderPartial('form_collectionTab', $data, true),
            Yii::t('jacq', 'Derivatives') => $this->renderPartial('form_derivativesTab', $data, true),
            Yii::t('jacq', 'Inventory') => (!$model_livingPlant->isNewRecord) ? $this->renderPartial('form_inventoryTab', $data, true) : '',
        ),
        // additional javascript options for the tabs plugin
        'options' => array(
        ),
    ));
    ?>

    <?php //echo $form->errorSummary($model_acquisitionDate, $model_acquisitionEvent, $model_livingPlant,$model_botanicalObject);    ?>

    <br />
    <?php require('form_importProperties.php'); ?>

    <div class="row buttons">
        <?php
        if (!$model_livingPlant->isNewRecord) {
            echo Html::button(
                    Yii::t('jacq', 'Copy & New'), array(
                'type' => 'button',
                'onclick' => 'window.location.href = "' . $this->createUrl('copyAndNew', array(
                    'living_plant_id' => $model_livingPlant->id
                        )
                ) . '"'
                    )
            );
        }
        ?>
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

    <?php
    // widget for scientific name information editing
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'scientific_name_information_dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => Yii::t('jacq', 'Scientific Name Information'),
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
                    'click' => new CJavaScriptExpression('scientificNameInformationSave')
                ),
            ),
            'open' => new CJavaScriptExpression('scientificNameInformationOpen'),
            'close' => new CJavaScriptExpression('scientificNameInformationClose'),
        ),
    ));
    ?>
    <div id="scientific_name_information_view" style="height: 300px;"></div>
    <?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>

    <?php
    // widget for scientific name information editing
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'vegetative_dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => Yii::t('jacq', 'Vegetative Derivative'),
            'autoOpen' => false,
            'resizable' => false,
            'width' => 800,
            'buttons' => array(
                array(
                    'text' => Yii::t('jacq', 'Close'),
                    'click' => new CJavaScriptExpression('vegetativeDialogClose')
                ),
                array(
                    'text' => Yii::t('jacq', 'Save'),
                    'click' => new CJavaScriptExpression('vegetativeDialogSave')
                ),
            )
        ),
    ));
    ?>
    <div id="scientific_name_information_view" style="height: 300px;"></div>
    <?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
</div><!-- form -->


<!-- List of source-codes for institutions to auto-fill the IPEN number -->
<script type="text/javascript">
    var ipen_codes = {
<?php
$ipen_code_models = Organisation::model()->findAll();
foreach ($ipen_code_models as $ipen_code_model) {
    echo "'" . $ipen_code_model->id . "': '" . $ipen_code_model->getIpenCode() . "',\n";
}
?>
        '0': ''
    };

    /**
     * Called when the scientific name information dialog is opened (for reloading)
     */
    function scientificNameInformationOpen(event, ui) {
        // load authorization view and assign it to div
        $('#scientific_name_information_view').load('<?php echo $this->createUrl('scientificNameInformation/ajaxUpdate', array('scientific_name_id' => '')); ?>' + $('#BotanicalObject_scientific_name_id').val());
    }

    /**
     * Called when the scientific name information dialog is closed (empty content)
     */
    function scientificNameInformationClose(event, ui) {
        $('#scientific_name_information_view').html('');
    }

    /**
     * Called when the scientific name informations are saved
     */
    function scientificNameInformationSave(event, ui) {
        // keep reference to dialog
        var self = this;

        // get all select values for sending to the server
        var formData = $('#scientific-name-information-update-form').serialize();

        // disable the whole form
        $('#scientific-name-information-update-form').attr('disabled', 'disabled');

        // send the request to the server
        $.post(
                $('#scientific-name-information-update-form').attr('action'),
                formData,
                function (data, textStatus, jqXHR) {
                    // close the calling dialog
                    $(self).dialog('close');
                }
        );
    }

    /**
     * Called when the authorization dialog is opened (for reloading)
     */
    function authorizationOpen(event, ui) {
        // load authorization view and assign it to div
        $('#authorization_view').load('<?php echo $this->createUrl('authorization/ajaxBotanicalObjectAccess', array('botanical_object_id' => $model_botanicalObject->id)); ?>');
    }

    /**
     * Called when the authorization dialog is closed (empty content)
     */
    function authorizationClose(event, ui) {
        $('#authorization_view').html('');
    }

    /**
     * Called when the authorization settings are saved
     */
    function authorizationSave(event, ui) {
        // keep reference to dialog
        var self = this;

        // get all select values for sending to the server
        var formData = {};
        $('#authorization_form select').each(function () {
            formData[$(this).attr('name')] = $(this).val();
        });

        // disable the whole form
        $('#authorization_form select').attr('disabled', 'disabled');

        // send the request to the server
        $.post(
                '<?php echo $this->createUrl('authorization/ajaxBotanicalObjectAccessSave', array('botanical_object_id' => $model_botanicalObject->id)); ?>',
                formData,
                function (data, textStatus, jqXHR) {
                    // close the calling dialog
                    $(self).dialog('close');
                }
        );
    }

    /**
     * Handle dialog closing for vegetative derivatives
     */
    function vegetativeDialogClose() {
        $('#vegetative_dialog').html('');
        $('#vegetative_dialog').dialog('close');
    }

    /**
     * Handle saving of vegetative derivatives
     */
    function vegetativeDialogSave() {
        // keep reference to dialog
        var self = this;

        // get all select values for sending to the server
        var formData = $('#vegetative-form').serialize();
        $('#vegetative-form select').each(function () {
            formData[$(this).attr('name')] = $(this).val();
        });

        // disable the whole form
        $('#vegetative-form').attr('disabled', 'disabled');
        $('#vegetative-form select').attr('disabled', 'disabled');

        // send the request to the server
        $.post(
                $('#vegetative-form').attr('action'),
                formData,
                function (data, textStatus, jqXHR) {
                    // we do not expect any response, if we receive one add it as content and keep the dialog open
                    if (data === null || data === '') {
                        // refresh list of vegetatives
                        refreshVegetatives();

                        // close the calling dialog
                        $(self).dialog('close');
                    } else {
                        $(self).html(data);
                    }
                }
        );
    }

    /**
     * Open the organisation dialog
     * @param String id_target jQuery-Selector of element to set the selected id to
     * @param String name_target jQuery-Selector of element to set the selected name to
     * @returns {undefined}
     */
    function organisationDialogOpen(id_target, name_target) {
        $('#organisation_select_dialog').data('id_target', id_target);
        $('#organisation_select_dialog').data('name_target', name_target);
        $('#organisation_select_dialog').dialog('open');
    }

    /**
     * Refresh list of vegetative derivatives
     * @returns {undefined}
     */
    function refreshVegetatives() {
        var living_plant_id = <?php echo intval($model_livingPlant->id); ?>;
        if (living_plant_id > 0) {
            $('#derivatives_vegetative').load('<?php echo $this->createUrl('livingPlant/ajaxVegetativeList', array('living_plant_id' => $model_livingPlant->id)); ?>');
        }
    }

    // Bind to change event of institution select
    $(document).ready(function () {
        // initialize jsTree for organisation
        $('#organisation_tree').jstree({
            "json_data": {
                "ajax": {
                    "url": "index.php?r=jSONOrganisation/japi&action=getChildren",
                    "data": function (n) {
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
        $('#organisation_tree a').live('click', function () {
            // update references to organisation
            $($('#organisation_select_dialog').data('id_target')).val($(this).attr('data-organisation-id'));
            $($('#organisation_select_dialog').data('name_target')).val($(this).text());
            $('#organisation_select_dialog').dialog('close');

            // update IPEN code, only if not locked
            if (!$('#LivingPlant_ipen_locked').is(':checked')) {
                $("#LivingPlant_ipenNumberInstitutionCode").val(ipen_codes[$("#BotanicalObject_organisation_id").val()]);
            }
            return false;
        });

        // bind to new location event
        $('#locationName').bind('autocompleteselect', function (event, ui) {
            if (typeof ui.item.countryCode !== "undefined" && !$("#LivingPlant_ipen_locked").is(":checked")) {
                $("#LivingPlant_ipenNumberCountryCode").val(ui.item.countryCode);
            }
        });

        // refresh vegetatives
        refreshVegetatives();
    });

</script>
