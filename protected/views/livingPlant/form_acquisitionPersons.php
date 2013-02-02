<div class="form">
    <?php echo CHtml::activeLabelEx(Person::model(), 'name'); ?>
    <?php
    $model_acquisitionPersons = array();
    if( isset($model_livingPlant->id0->acquisitionEvent->tblPeople) ) {
        $model_acquisitionPersons = $model_livingPlant->id0->acquisitionEvent->tblPeople;
    }

    foreach( $model_acquisitionPersons as $model_acquisitionPerson ) {
        require('form_acquisitionPerson.php');
    }
    ?>
    <div id="acquisitionPerson_addRow">
    <?php
    echo CHtml::imageButton('images/add.png', array(
        'onclick' => "
            $.ajax({
                url: 'index.php?r=livingPlant/ajaxAcquisitionPerson'
            }).done(function(data) {
                $('#acquisitionPerson_addRow').before(data);
            });
            return false;"
    ));
    ?>
    </div>
</div>
