<div>
    Access for groups
    <ul>
        <?php
        foreach($groups as $groupName => $group) {
            ?>
            <li>
                <select id='<?php echo $group->name; ?>'>
                    <option value='auto'>auto</option>
                    <option value='yes'>yes</option>
                    <option value='no'>no</option>
                </select>
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
                <select id='<?php echo $user->id; ?>'>
                    <option value='auto'>auto</option>
                    <option value='yes'>yes</option>
                    <option value='no'>no</option>
                </select>
                <?php echo $user->username; ?>
            </li>
            <?php
        }
        ?>
    </ul>
</div>
