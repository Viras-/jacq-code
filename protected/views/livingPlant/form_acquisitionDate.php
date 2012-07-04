<div class="row">
    <table style="width: auto;">
        <tr>
            <td>
                <?php echo $form->labelEx($model_acquisitionDate, 'date'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'AcquisitionDate[date]',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd',
                        'changeMonth' => true,
                        'changeYear' => true
                    ),
                    'htmlOptions' => array(
                        
                    ),
                    'value' => $model_acquisitionDate->date,
                ));
                ?>
                <?php echo $form->error($model_acquisitionDate, 'date'); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model_acquisitionDate, 'custom'); ?>
                <?php echo $form->textField($model_acquisitionDate, 'custom'); ?>
                <?php echo $form->error($model_acquisitionDate, 'custom'); ?>
            </td>
        </tr>
    </table>
</div>

<hr/>
