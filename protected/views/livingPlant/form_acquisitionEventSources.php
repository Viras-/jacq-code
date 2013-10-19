<div class="form" style="display: table; width: 100%;">
    <div style="display: table-row;">
        <div style="width: 40%; display: table-cell;"><?php echo CHtml::activeLabelEx(AcquisitionEventSource::model(), 'source_date', array("style" => "display: inline;")); ?></div>
        <div style="width: 40%; display: table-cell;"><?php echo CHtml::activeLabelEx(AcquisitionEventSource::model(), 'source_name', array("style" => "display: inline;")); ?></div>
        <div style="display: table-cell;"><?php echo CHtml::activeLabelEx(AcquisitionEventSource::model(), 'actions', array("style" => "display: inline;")); ?></div>
    </div>
    <?php
    $models_acquisitionEventSource = $model_acquisitionEvent->acquisitionEventSources;

    foreach( $models_acquisitionEventSource as $model_acquisitionEventSource ) {
        require('form_acquisitionEventSource.php');
    }
    ?>
    <div id="acquisitionEventSource_lastRow">
    <?php
    echo CHtml::imageButton('images/add.png', array(
        'onclick' => "
            $.ajax({
                url: '" . $this->createUrl('livingPlant/ajaxAcquisitionEventSource') . "'
            }).done(function(data) {
                $('#acquisitionEventSource_lastRow').before(data);
            });
            return false;"
    ));
    ?>
    </div>
</div>
