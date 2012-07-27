<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'living-plant-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php //echo $form->errorSummary($model_acquisitionDate, $model_acquisitionEvent, $model_livingPlant,$model_botanicalObject);  ?>

    <fieldset>
        <legend>acquisition</legend>
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

    <div class="row buttons">
        <?php echo CHtml::submitButton($model_livingPlant->isNewRecord ? 'Create' : 'Save'); ?>
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