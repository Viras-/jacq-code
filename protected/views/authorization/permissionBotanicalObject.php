<div>
    Access for groups
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
                            NULL => 'auto',
                            true => 'yes',
                            false => 'no'
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
    Access for users
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
                            NULL => 'auto',
                            true => 'yes',
                            false => 'no'
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
