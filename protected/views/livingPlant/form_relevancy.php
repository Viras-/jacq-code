<!-- relevancy -->
<div class="row">
    <?php echo $form->labelEx(Relevancy::model(), 'relevancy_type_id'); ?>
    <?php
    // Find all checked relevancy types for this entry
    $selected_relevancyTypes = array();
    if (!$model_livingPlant->isNewRecord) {
        $models_relevancy = Relevancy::model()->findAll('living_plant_id=:living_plant_id', array(':living_plant_id' => $model_livingPlant->id));

        // Add all selected relevancy types to array
        foreach ($models_relevancy as $model_relevancy) {
            $selected_relevancyTypes[] = $model_relevancy->relevancy_type_id;
        }
    }

    // Create checkbox list for all relevancy type entries
    $models_relevancyType = RelevancyType::model()->findAllByAttributes(array('important' => $important));
    echo CHtml::checkBoxList(
            'RelevancyType', $selected_relevancyTypes, Html::listDataSorted(
                    $models_relevancyType, 'id', 'typeTranslated'
            ), array(
        'labelOptions' => array(
            'style' => 'display: inline'
        )
            )
    );
    ?>
</div>
