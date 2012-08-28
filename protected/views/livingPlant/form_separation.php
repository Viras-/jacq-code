<div class="row">
    <table style="width: auto;">
        <?php
        $model_separations = $model_botanicalObject->separations;
        $model_separations[] = new Separation;  // Add one new entry for adding
        
        foreach( $model_separations as $i => $model_separation ) {
        ?>
        <tr>
            <td>
                <?php
                $separation_types = CHtml::listData(SeparationType::model()->findAll(), 'id', 'type');
                
                // check if we have a valid id already, if not skip the hidden field
                if( $model_separation->id > 0 ) {
                    echo $form->hiddenField($model_separation, "[$i]id");
                }
                else {
                    $separation_types = array( '' => 'None' ) + $separation_types;
                }
                
                echo $form->labelEx($model_separation, 'separation_type_id');
                echo $form->dropDownList($model_separation, "[$i]separation_type_id", $separation_types );
                echo $form->error($model_separation, 'separation_type_id');
                ?>
            </td>
            <td>
                <?php
                echo $form->labelEx($model_separation, 'date');
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => "Separation[$i][date]",
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd',
                        'changeMonth' => true,
                        'changeYear' => true,
                    ),
                    'htmlOptions' => array(
                    ),
                    'value' => $model_separation->date,
                ));
                echo $form->error($model_separation, 'date');
                ?>
            </td>
            <td>
                <?php
                echo $form->labelEx($model_separation, 'annotation');
                echo $form->textField($model_separation, "[$i]annotation");
                echo $form->error($model_separation, 'annotation');
                ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>
