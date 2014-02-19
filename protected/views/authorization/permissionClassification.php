<?php
$form = $this->beginWidget(
        'CActiveForm',
        array(
            'id' => 'authorization_form',
            'enableAjaxValidation' => false,
            'htmlOptions' => array(
                'onsubmit' => "return false;"
            )
        )
);

// output hidden field for tax_syn_ID
echo CHtml::hiddenField('tax_syn_ID', $tax_syn_ID);
?>
<div>
    <?php echo Yii::t('jacq','Access for groups'); ?>
    <ul>
        <?php
        foreach($groups as $groupName => $group) {
            ?>
            <li>
                <?php
                echo CHtml::dropDownList(
                        'Groups[' . $group->name . ']',
                        Yii::app()->authorization->classificationAccessGroup($group->name,$tax_syn_ID),
                        array(
                            "" => Yii::t('jacq', 'auto'),
                            1 => Yii::t('jacq', 'yes'),
                            0 => Yii::t('jacq', 'no')
                        )
                );
                ?>
                <?php echo $groupName; ?>
            </li>
            <?php
        }
        ?>
    </ul>
</div>
<div>
    <?php echo Yii::t('jacq','Access for users'); ?>
    <ul>
        <?php
        foreach($users as $user) {
            ?>
            <li>
                <?php
                echo CHtml::dropDownList(
                        'Users[' . $user->id . ']',
                        Yii::app()->authorization->classificationAccessUser($user->id,$tax_syn_ID),
                        array(
                            "" => Yii::t('jacq', 'auto'),
                            1 => Yii::t('jacq', 'yes'),
                            0 => Yii::t('jacq', 'no')
                        )
                );
                ?>
                <?php echo $user->username; ?>
            </li>
            <?php
        }
        ?>
    </ul>
</div>
<?php $this->endWidget(); ?>
