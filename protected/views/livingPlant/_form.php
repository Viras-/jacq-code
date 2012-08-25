<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'living-plant-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'data-plus-as-tab' => "true"
        )
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

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
        <legend>separation</legend>
        <?php
        require('form_separation.php');
        ?>
    </fieldset>
    <fieldset>
        <legend>living plant</legend>
        <?php
        require('form_botanicalObject.php');
        require('form_livingPlant.php');
        ?>
    </fieldset>
    <fieldset>
        <legend>certificates</legend>
        <?php
        require('form_certificates.php');
        ?>
    </fieldset>
    <fieldset>
        <legend>tree record</legend>
        <?php
        require('form_treeRecord.php');
        ?>
    </fieldset>
    <fieldset>
        <legend>relevancy</legend>
        <?php
        require('form_relevancy.php');
        ?>
    </fieldset>
    <fieldset>
        <legend>sex</legend>
        <?php
        require('form_sex.php');
        ?>
    </fieldset>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model_livingPlant->isNewRecord ? 'Create' : 'Save', array('data-plus-as-tab' => "false")); ?>
    </div>

    <?php $this->endWidget(); ?>

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
    <iframe id="tree_record_download_iframe" scrolling="no" src="about:blank" style="display: none;">No iFrame support in your browser</iframe>
</div><!-- form -->