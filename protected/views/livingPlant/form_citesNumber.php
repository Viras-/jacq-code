<div class="row">
    <table style="width: auto;">
        <?php
        if (!$model_livingPlant->isNewRecord) {
            ?>
            <tr>
                <td>
                    <table style="width: auto;">
                        <tr>
                            <th>number</th>
                            <th></th>
                        </tr>
                        <?php
                        // Find all previously entered cites numbers
                        $models_citesNumber = CitesNumber::model()->findAll('living_plant_id=:living_plant_id', array(':living_plant_id' => $model_livingPlant->id));
                        foreach ($models_citesNumber as $saved_model_citesNumber) {
                            ?>
                            <tr id="cites_number_<?php echo $saved_model_citesNumber->id; ?>">
                                <td><?php echo $saved_model_citesNumber->cites_number ?></td>
                                <td>
                                    <?php
                                    // Create delete button for cites number
                                    echo CHtml::ajaxButton(
                                            'cites_number_delete',
                                            $this->createUrl('livingPlant/removeCitesNumber',array('id' => $saved_model_citesNumber->id)),
                                            array( 'replace' => '#cites_number_' . $saved_model_citesNumber->id ),
                                            array( 'type' => 'image', 'src' => 'images/delete.png' )
                                    );
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>
                <?php echo $form->labelEx(CitesNumber::model(), 'cites_number'); ?>
                <?php echo $form->textField(CitesNumber::model(), 'cites_number' ); ?>
                <?php echo $form->error(CitesNumber::model(), 'cites_number'); ?>
            </td>
        </tr>
    </table>
</div>
