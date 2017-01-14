<div class="form">
    <?php echo CHtml::activeLabelEx(DerivativeVegetative::model(), 'derivative_vegetative_id'); ?>
    <div id="derivatives_vegetative">
    </div>
    <div id="vegetative_addRow">
        <?php
        if (!$model_livingPlant->isNewRecord) {
            echo CHtml::imageButton('images/add.png', array(
                'onclick' => "
            $.ajax({
                url: 'index.php?r=livingPlant/ajaxVegetative&derivative_vegetative_id=0&living_plant_id=" . $model_livingPlant->id . "'
            }).done(function(data) {
                $('#vegetative_dialog').html(data);
                $('#vegetative_dialog').dialog('open');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $('#vegetative_dialog').html(textStatus);
                $('#vegetative_dialog').dialog('open');
            });
            return false;"
            ));
        }
        else {
            echo Html::encode(Yii::t('jacq', 'Please save the entry before adding vegetative derivatives.'));
        }
        ?>
    </div>
</div>
