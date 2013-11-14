<div class="row">
    <table style="width: auto;">
        <?php
        if (!$model_livingPlant->isNewRecord) {
            ?>
            <tr>
                <td colspan="2">
                    <table style="width: auto;">
                        <tr>
                            <th><?php echo $form->labelEx(TreeRecordFile::model(), 'year'); ?></th>
                            <th><?php echo $form->labelEx(TreeRecordFile::model(), 'name'); ?></th>
                            <th><?php echo $form->labelEx(TreeRecordFilePage::model(), 'page'); ?></th>
                            <th><?php echo $form->labelEx(LivingPlantTreeRecordFilePage::model(), 'corrections_date'); ?></th>
                            <th></th>
                        </tr>
                        <?php
                        $models_livingPlantTreeRecordFilePage = LivingPlantTreeRecordFilePage::model()->findAll('living_plant_id=:living_plant_id', array(':living_plant_id' => $model_livingPlant->id));

                        foreach ($models_livingPlantTreeRecordFilePage as $model_livingPlantTreeRecordFilePage) {
                            $model_treeRecordFilePage = TreeRecordFilePage::model()->findByPk($model_livingPlantTreeRecordFilePage->tree_record_file_page_id);
                            $model_treeRecordFile = TreeRecordFile::model()->findByPk($model_treeRecordFilePage->tree_record_file_id);
                            ?>
                            <tr>
                                <td><?php echo $model_treeRecordFile->year ?></td>
                                <td><?php echo $model_treeRecordFile->name ?></td>
                                <td><?php echo $model_treeRecordFilePage->page ?></td>
                                <td>
                                    <?php echo CHtml::checkBox('LivingPlantTreeRecordFilePage[' . $model_livingPlantTreeRecordFilePage->id . '][corrections_done]', $model_livingPlantTreeRecordFilePage->corrections_done); ?>
                                    <?php
                                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                        'name' => 'LivingPlantTreeRecordFilePage[' . $model_livingPlantTreeRecordFilePage->id . '][corrections_date]',
                                        // additional javascript options for the date picker plugin
                                        'options' => array(
                                            'showAnim' => 'fold',
                                            'dateFormat' => 'yy-mm-dd',
                                            'changeMonth' => true,
                                            'changeYear' => true
                                        ),
                                        'htmlOptions' => array(
                                            'size' => 10
                                        ),
                                        'value' => $model_livingPlantTreeRecordFilePage->corrections_date,
                                    ));
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    // Create view button for each page
                                    echo CHtml::button(
                                            'view', array(
                                                'type' => 'image',
                                                'src' => 'images/page_green.png',
                                                'onclick' => "$('#tree_record_view_dialog_iframe').attr( 'src', '" . $this->createUrl( 'livingPlant/treeRecordFilePageView', array( 'tree_record_file_page_id' => $model_treeRecordFilePage->id ) ) . "' ); $( '#tree_record_view_dialog' ).dialog( 'open' ); return false;"
                                            )
                                    );

                                    // Create download button for each page
                                    echo CHtml::button(
                                            'view', array(
                                                'type' => 'image',
                                                'src' => 'images/page_save.png',
                                                'onclick' => "$('#tree_record_download_iframe').attr( 'src', '" . $this->createUrl( 'livingPlant/treeRecordFilePageView', array( 'tree_record_file_page_id' => $model_treeRecordFilePage->id, 'download' => '1' ) ) . "' ); return false;"
                                            )
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
                <?php echo $form->labelEx(TreeRecordFile::model(), 'name'); ?>
                <?php
                // Find all available files
                $treeRecordFiles = TreeRecordFile::model()->findAll(array('order' => 'year'));
                $treeRecordFilesListData = array('' => Yii::t('jacq', 'none'));
                foreach ($treeRecordFiles as $treeRecordFile) {
                    $treeRecordFilesListData[$treeRecordFile->id] = '[' . $treeRecordFile->year . '] ' . $treeRecordFile->name;
                }

                // File select dropdown
                echo CHtml::dropDownList(
                        'TreeRecord[tree_record_file_id]', '', $treeRecordFilesListData, array(
                    'ajax' => array(
                        'type' => 'POST',
                        'url' => $this->createUrl('livingPlant/treeRecordFilePages'),
                        'update' => '#TreeRecord_tree_record_file_page_id',
                    )
                        )
                );
                ?>
            </td>
            <td>
                <?php echo $form->labelEx(TreeRecordFilePage::model(), 'page'); ?>
                <?php echo CHtml::dropDownList('TreeRecord[tree_record_file_page_id]', '', array()); ?>
            </td>
        </tr>
    </table>
</div>
