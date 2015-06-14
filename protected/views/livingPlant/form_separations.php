<div class="form">
    <div>
        <!-- header row for div-based table -->
        <div>
            <div class="separation_td">
                <?php echo CHtml::activeLabelEx(Separation::model(), 'separation_type_id'); ?>
            </div>
            <div class="separation_td">
                <?php echo CHtml::activeLabelEx(Separation::model(), 'date'); ?>
            </div>
            <div class="separation_td">
                <?php echo CHtml::activeLabelEx(Separation::model(), 'annotation'); ?>
            </div>
        </div>
        <?php
        foreach( $model_botanicalObject->separations as $model_separation ) {
            require('form_separation.php');
        }
        ?>
        <div id="separation_addRow">
            <?php
            echo CHtml::imageButton('images/add.png', array(
                'onclick' => "
                    $.ajax({
                        url: 'index.php?r=livingPlant/ajaxSeparation'
                    }).done(function(data) {
                        $('#separation_addRow').before(data);
                    });
                    return false;"
            ));
            ?>
        </div>
    </div>
</div>
