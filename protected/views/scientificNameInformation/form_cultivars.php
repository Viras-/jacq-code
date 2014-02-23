<div class="form">
    <?php echo CHtml::activeLabelEx(Cultivar::model(), 'cultivar'); ?>
    <?php
    foreach( $model_scientificNameInformation->cultivars as $model_cultivar ) {
        require('form_cultivar.php');
    }
    ?>
    <div id="cultivar_addRow">
    <?php
    echo CHtml::imageButton('images/add.png', array(
        'onclick' => "
            $.ajax({
                url: 'index.php?r=scientificNameInformation/ajaxCultivar'
            }).done(function(data) {
                $('#cultivar_addRow').before(data);
            });
            return false;"
    ));
    ?>
    </div>
</div>
