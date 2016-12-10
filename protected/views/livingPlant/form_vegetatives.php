<div class="form">
    <?php echo CHtml::activeLabelEx(DerivativeVegetative::model(), 'derivative_vegetative_id'); ?>
    <?php
    foreach ($model_livingPlant->derivativeVegetatives as $model_derivativeVegetative) {
        require('form_vegetative.php');
    }
    ?>
    <div id="vegetative_addRow">
        <?php
        echo CHtml::imageButton('images/add.png', array(
            'onclick' => "
            $.ajax({
                url: 'index.php?r=livingPlant/ajaxVegetative'
            }).done(function(data) {
                $('#vegetative_addRow').before(data);
            });
            return false;"
        ));
        ?>
    </div>
</div>
