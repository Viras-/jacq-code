<div class="row">
    <table style="width: auto;">
        <?php
        if (!$model_livingPlant->isNewRecord) {
            ?>
            <tr>
                <td colspan="3">
                    <table style="width: auto;">
                        <tr>
                            <th>year</th>
                            <th>name</th>
                            <th>page</th>
                            <th>result</th>
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
                                <td><?php echo $model_livingPlantTreeRecordFilePage->result ?></td>
                                <td>
                                    <?php
                                    // Create view button for each page
                                    $gridViewAssetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets'));
                                    echo CHtml::button(
                                            'view', array(
                                                'type' => 'image',
                                                'src' => $gridViewAssetsUrl . '/gridview/view.png',
                                                'onclick' => "$('#tree_record_view_dialog_iframe').attr( 'src', '" . $this->createUrl( 'livingPlant/treeRecordFilePageView', array( 'tree_record_file_page_id' => $model_treeRecordFilePage->id ) ) . "' ); $( '#tree_record_view_dialog' ).dialog( 'open' ); return false;"
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
                $treeRecordFilesListData = array('' => 'None');
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
            <td>
                <?php echo $form->labelEx(LivingPlantTreeRecordFilePage::model(), 'result'); ?>
                <?php echo CHtml::checkBox('TreeRecord[result]'); ?>
            </td>
        </tr>
    </table>
</div>
