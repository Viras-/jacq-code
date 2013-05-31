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
                        'grp_' . $group->name,
                        Yii::app()->authorization->botanicalObjectAccessGroup($group->name,$botanical_object_id),
                        array(
                            NULL => Yii::t('jacq', 'auto'),
                            true => Yii::t('jacq', 'yes'),
                            false => Yii::t('jacq', 'no')
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
                        'usr_' . $user->id,
                        Yii::app()->authorization->botanicalObjectAccessUser($user->id,$botanical_object_id),
                        array(
                            NULL => Yii::t('jacq', 'auto'),
                            true => Yii::t('jacq', 'yes'),
                            false => Yii::t('jacq', 'no')
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