<div class="form">
    <?php echo CHtml::activeLabelEx(AlternativeAccessionNumber::model(), 'number'); ?>
    <?php
    foreach( $model_livingPlant->alternativeAccessionNumbers as $model_alternativeAccessionNumber ) {
        require('form_alternativeAccessionNumber.php');
    }
    ?>
    <div id="alternativeAccessionNumber_addRow">
    <?php
    echo CHtml::imageButton('images/add.png', array(
        'onclick' => "
            $.ajax({
                url: 'index.php?r=livingPlant/ajaxAlternativeAccessionNumber'
            }).done(function(data) {
                $('#alternativeAccessionNumber_addRow').before(data);
            });
            return false;"
    ));
    ?>
    </div>
</div>
