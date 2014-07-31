<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php
        if( $model->isNewRecord ) {
            echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 128));
        }
        else {
            echo CHtml::encode($model->username); 
        }
        ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'newPassword'); ?>
        <?php echo $form->passwordField($model, 'newPassword', array('size' => 60, 'maxlength' => 64)); ?>
        <?php echo $form->error($model, 'newPassword'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'force_password_change'); ?>
        <?php echo $form->checkBox($model, 'force_password_change'); ?>
        <?php echo $form->error($model, 'force_password_change'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'user_type_id'); ?>
        <?php echo $form->dropDownList($model, 'user_type_id', Html::listDataSorted(UserType::model()->findAll(), 'user_type_id', 'typeTranslated')); ?>
        <?php echo $form->error($model, 'user_type_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'employment_type_id'); ?>
        <?php echo $form->dropDownList($model, 'employment_type_id', Html::listDataSorted(EmploymentType::model()->findAll(), 'employment_type_id', 'typeTranslated')); ?>
        <?php echo $form->error($model, 'employment_type_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'title_prefix'); ?>
        <?php echo $form->textField($model, 'title_prefix', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'title_prefix'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'firstname'); ?>
        <?php echo $form->textField($model, 'firstname', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'firstname'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'lastname'); ?>
        <?php echo $form->textField($model, 'lastname', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'lastname'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'title_suffix'); ?>
        <?php echo $form->textField($model, 'title_suffix', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'title_suffix'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'birthdate'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'User[birthdate]',
            // additional javascript options for the date picker plugin
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'changeMonth' => true,
                'changeYear' => true
            ),
            'htmlOptions' => array(

            ),
            'value' => $model->birthdate,
        ));
        ?>
        <?php echo $form->error($model, 'birthdate'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'organisation_id'); ?>
        <?php echo CHtml::textField('User_organisation_name', (isset($model->organisation)) ? $model->organisation->description : '', array('readonly' => 'readonly')); ?>
        <?php echo $form->hiddenField($model, 'organisation_id'); ?>
        <a href="#" onclick="$('#organisation_select_dialog').dialog('open'); return false;"><?php echo Yii::t('jacq','Change'); ?></a>
        <?php echo $form->error($model, 'organisation_id'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'groups'); ?>
        <?php
        // fetch available groups and prepare them for display
        $groupItems = Yii::app()->authorization->listGroups();
        $groups = array();
        foreach( $groupItems as $groupName => $groupItem ) {
            $groups[$groupItem->name] = $groupName;
        }
        // fetch assigned groups
        $groupItems = $model->groups;
        $assignedGroups = array();
        foreach( $groupItems as $groupName => $groupItem ) {
            $assignedGroups[] = $groupName;
        }
        
        // display checkboxlist
        echo CHtml::checkBoxList(
                'User[groups]',
                $assignedGroups,
                $groups,
                array(
                    'labelOptions' => array('style' => 'display: inline'),
                    'separator' => ''
                )
        );
        ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('jacq','Create') : Yii::t('jacq','Save')); ?>
    </div>

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

    <?php $this->endWidget(); ?>
</div><!-- form -->

<script type="text/javascript">
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
            $('#User_organisation_id').val( $(this).attr('data-organisation-id') );
            $('#User_organisation_name').val( $(this).text() );
            $('#organisation_select_dialog').dialog('close');
            return false;
        });
    });
    
</script>
