<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php echo $form->labelEx(Sex::model(), 'sex_id'); ?>
                <?php
                // Find all checked sex for this entry
                $selected_sex = array();
                if (!$model_livingPlant->isNewRecord) {
                    $models_botanicalObjectSex = BotanicalObjectSex::model()->findAll('botanical_object_id=:botanical_object_id', array(':botanical_object_id' => $model_livingPlant->id));

                    // Add all selected relevancy types to array
                    foreach ($models_botanicalObjectSex as $model_botanicalObjectSex) {
                        $selected_sex[] = $model_botanicalObjectSex->sex_id;
                    }
                }

                // Create checkbox list for all relevancy type entries
                $models_sex = Sex::model()->findAll();
                $list_sex = CHtml::listData($models_sex, 'id', 'sex');
                echo CHtml::checkBoxList('Sex', $selected_sex, $list_sex, array('labelOptions' => array('style' => 'display: inline')));
                ?>
            </td>
        </tr>
    </table>
</div>
