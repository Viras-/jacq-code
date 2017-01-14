<?php
// check if we have a valid id already
if ($model_derivativeVegetative->derivative_vegetative_id > 0) {
    $model_form_id = $model_derivativeVegetative->derivative_vegetative_id;
    ?>
    <div id="vegetative_row_<?php echo $model_form_id; ?>" class="row" style="padding-bottom: 5px;">
        <div class="vegetative_td">
            <?php echo CHtml::encode($model_derivativeVegetative->cultivation_date); ?>
        </div>
        <div class="vegetative_td">
            <?php echo CHtml::encode($model_derivativeVegetative->accession_number); ?>
        </div>
        <div class="vegetative_td">
            <?php echo CHtml::encode($model_derivativeVegetative->organisation->description); ?>
        </div>
        <div class="vegetative_td">
            <?php
            echo CHtml::imageButton('images/pencil.png', array(
                'onclick' => "
                    $.ajax({
                        url: 'index.php?r=livingPlant/ajaxVegetative&derivative_vegetative_id=" . $model_form_id . "&living_plant_id=0'
                    }).done(function(data) {
                        $('#vegetative_dialog').html(data);
                        $('#vegetative_dialog').dialog('open');
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        $('#vegetative_dialog').html(textStatus);
                        $('#vegetative_dialog').dialog('open');
                    });
                    return false;",
                'style' => 'margin: 0;'
            ));
            ?>
            &nbsp;
            <?php
            echo CHtml::imageButton('images/delete.png', array(
                'onclick' => "
                    $.ajax({
                        url: 'index.php?r=livingPlant/ajaxVegetativeDelete&derivative_vegetative_id=" . $model_form_id . "'
                    }).done(function(data) {
                        $('#vegetative_row_{$model_form_id}').hide();
                    });

                    return false;",
                'style' => 'margin: 0;'
            ));
            ?>
        </div>
    </div>
    <?php
}
else {
    Yii::log("Invalid model passed to vegetative form", CLogger::LEVEL_ERROR);
}
