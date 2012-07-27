<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php echo $form->labelEx(Relevancy::model(), 'relevancy_type_id'); ?>
                <?php
                // Find all checked relevancy types for this entry
                $selected_relevancyTypes = array();
                if (!$model_livingPlant->isNewRecord) {
                    $models_relevancy = Relevancy::model()->findAll('livingplant_id=:livingplant_id', array(':livingplant_id' => $model_livingPlant->id));

                    // Add all selected relevancy types to array
                    foreach ($models_relevancy as $model_relevancy) {
                        $selected_relevancyTypes[] = $model_relevancy->relevancy_type_id;
                    }
                }

                // Create checkbox list for all relevancy type entries
                $models_relevancyType = RelevancyType::model()->findAll();
                $list_relevancyType = CHtml::listData($models_relevancyType, 'id', 'type');
                echo CHtml::checkBoxList('RelevancyType', $selected_relevancyTypes, $list_relevancyType, array('labelOptions' => array('style' => 'display: inline')));
                ?>
            </td>
        </tr>
    </table>
</div>
