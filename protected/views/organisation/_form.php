<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'garden-site-form',
	'enableAjaxValidation'=>false,
)); ?>

        <?php
        if( !$model->isNewRecord ) {
        ?>
        <div style="text-align: right;">
            <a href="#"><img src="images/user.png" border="0" onclick="$('#authorization_management_dialog').dialog('open'); return false;" /></a>
        </div>
        <?php
        }
        ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'department'); ?>
		<?php echo $form->textField($model,'department',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'department'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'greenhouse'); ?>
		<?php echo $form->checkbox($model,'greenhouse'); ?>
		<?php echo $form->error($model,'greenhouse'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'parent_organisation_id'); ?>
		<?php echo $form->dropDownList($model,'parent_organisation_id', Html::listDataSorted(Organisation::model()->findAll(), 'id', 'description', true)); ?>
		<?php echo $form->error($model,'parent_organisation_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gardener_id'); ?>
		<?php
                echo $form->dropDownList($model,'gardener_id', CHtml::listData(User::model()->findAll(), 'id', 'username'));
                ?>
		<?php echo $form->error($model,'gardener_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ipen_code'); ?>
		<?php echo $form->textField($model,'ipen_code',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'ipen_code'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('jacq', 'Create') : Yii::t('jacq', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

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
    /**
     * Called when the authorization dialog is opened (for reloading)
     */
    function authorizationOpen(event,ui) {
        // load authorization view and assign it to div
        $('#authorization_view').load('<?php echo $this->createUrl('authorization/ajaxOrganisationAccess', array('organisation_id' => $model->id)); ?>');
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
                '<?php echo $this->createUrl('authorization/ajaxOrganisationAccessSave', array('organisation_id' => $model->id)); ?>',
                formData,
                function(data, textStatus, jqXHR) {
                    // close the calling dialog
                    $(self).dialog('close');
                }
        );
    }
</script>
