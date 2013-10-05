<div class="form">
    <table>
        <tr>
            <td><?php echo CHtml::activeLabelEx(AcquisitionEventSource::model(), 'source_date'); ?></td>
            <td><?php echo CHtml::activeLabelEx(AcquisitionEventSource::model(), 'source_name'); ?></td>
            <td><?php echo CHtml::activeLabelEx(AcquisitionEventSource::model(), 'actions'); ?></td>
        </tr>
        <?php
        $models_acquisitionEventSource = $model_acquisitionEvent->acquisitionEventSources;

        foreach( $models_acquisitionEventSource as $model_acquisitionEventSource ) {
            require('form_acquisitionEventSource.php');
        }
        ?>
        <tr id="acquisitionEventSource_table_lastRow">
            <td colspan="3" style="text-align: left;">
                <?php
                echo CHtml::imageButton('images/add.png', array(
                    'onclick' => "
                        $.ajax({
                            url: 'index.php?r=livingPlant/ajaxAcquisitionEventSource'
                        }).done(function(data) {
                            $('#acquisitionEventSource_table_lastRow').before(data);
                        });
                        return false;"
                ));
                ?>
            </td>
        </tr>
    </table>
</div>
