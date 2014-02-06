<div class="form">
    <?php echo CHtml::activeLabelEx(Specimen::model(), 'herbar_number'); ?>
    <?php
    foreach( $model_botanicalObject->specimens as $model_specimen ) {
        require('form_specimen.php');
    }
    ?>
    <div id="specimen_addRow">
    <?php
    echo CHtml::imageButton('images/add.png', array(
        'onclick' => "
            $.ajax({
                url: 'index.php?r=livingPlant/ajaxSpecimen'
            }).done(function(data) {
                $('#specimen_addRow').before(data);
            });
            return false;"
    ));
    ?>
    </div>
</div>
