<?php
$this->breadcrumbs=array(
	Yii::t('jacq', 'Living Plants')=>array('index'),
	$model_livingPlant->id=>array('view','id'=>$model_livingPlant->id),
	Yii::t('jacq', 'View'),
);

$this->menu=array(
	array('label'=>Yii::t('jacq', 'Manage Living Plant'), 'url'=>array('admin')),
);

//$model_livingPlant = new LivingPlant();
?>

<h1><?php echo Yii::t('jacq', 'View Living Plant'); ?> <?php echo $model_livingPlant->id; ?></h1>

<div class="form">
    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Recording'); ?></legend>
        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0, 'recording_date'); ?>
            <?php echo CHtml::encode($model_livingPlant->id0->recording_date); ?>&nbsp;
        </div>
    </fieldset>
    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Acquisition'); ?></legend>
        <div class="row">
            <table style="width: auto;">
                <tr>
                    <td>
                        <?php echo CHtml::activeLabelEx($model_livingPlant->id0->acquisitionEvent->acquisitionDate, 'acquisition_date'); ?>
                        <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->acquisitionDate->date); ?>&nbsp;
                    </td>
                    <td>
                        <?php echo CHtml::activeLabelEx($model_livingPlant->id0->acquisitionEvent->acquisitionDate, 'custom'); ?>
                        <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->acquisitionDate->custom); ?>&nbsp;
                    </td>
                </tr>
            </table>
        </div>
        <hr/>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0->acquisitionEvent, 'acquisition_type_id'); ?>
            <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->acquisitionType->type); ?>&nbsp;
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0->acquisitionEvent, 'person_name'); ?>
            <?php
            // output each collector
            foreach( $model_livingPlant->id0->acquisitionEvent->tblPeople as $index => $model_person ) {
                echo CHtml::encode($model_person->name);
                ?>
                <br />
                <?php
            }
            ?>&nbsp;
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0->acquisitionEvent, 'number'); ?>
            <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->number); ?>&nbsp;
        </div>
        <hr />

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0->acquisitionEvent, 'location_id'); ?>
            <?php echo CHtml::encode(($model_livingPlant->id0->acquisitionEvent->location) ? $model_livingPlant->id0->acquisitionEvent->location->location : ''); ?>&nbsp;
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0->acquisitionEvent, 'altitude'); ?>
            <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->locationCoordinates->altitude_min); ?>
            -
            <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->locationCoordinates->altitude_max); ?>
        </div>

        <div class="row">
            <table>
                <tr>
                    <td>
                    <?php echo CHtml::activeLabelEx($model_livingPlant->id0->acquisitionEvent, 'latitude'); ?>
                    <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->locationCoordinates->latitude_degrees); ?>
                    <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->locationCoordinates->latitude_minutes); ?>
                    <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->locationCoordinates->latitude_seconds); ?>
                    <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->locationCoordinates->latitude_half); ?>&nbsp;
                    </td>
                    <td>
                    <?php echo CHtml::activeLabelEx($model_livingPlant->id0->acquisitionEvent, 'longitude'); ?>
                    <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->locationCoordinates->longitude_degrees); ?>
                    <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->locationCoordinates->longitude_minutes); ?>
                    <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->locationCoordinates->longitude_seconds); ?>
                    <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->locationCoordinates->longitude_half); ?>&nbsp;
                    </td>
                    <td>
                    <?php echo CHtml::activeLabelEx($model_livingPlant->id0->acquisitionEvent, 'exactness'); ?>
                    <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->locationCoordinates->exactness); ?>&nbsp;
                    </td>
                </tr>
            </table>
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0->acquisitionEvent, 'annotation'); ?>
            <?php echo CHtml::encode($model_livingPlant->id0->acquisitionEvent->annotation); ?>&nbsp;
        </div>
    </fieldset>
    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Separation'); ?></legend>
        <div class="row">
            <table style="width: auto;">
                <?php
                foreach( $model_livingPlant->id0->separations as $i => $model_separation ) {
                ?>
                <tr>
                    <td>
                        <?php
                        $separation_types = CHtml::listData(SeparationType::model()->findAll(), 'id', 'type');

                        // check if we have a valid id already, if not skip the hidden field
                        if( $model_separation->id > 0 ) {
                            echo $form->hiddenField($model_separation, "[$i]id");
                        }
                        else {
                            $separation_types = array( '' => 'None' ) + $separation_types;
                        }

                        echo CHtml::activeLabelEx($model_separation, 'separation_type_id');
                        echo CHtml::encode($model_separation->separationType->type);
                        ?>&nbsp;
                    </td>
                    <td>
                        <?php
                        echo CHtml::activeLabelEx($model_separation, 'date');
                        echo CHtml::encode($model_separation->date);
                        ?>&nbsp;
                    </td>
                    <td>
                        <?php
                        echo CHtml::activeLabelEx($model_separation, 'annotation');
                        echo CHtml::encode($model_separation->annotation);
                        ?>&nbsp;
                    </td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
   </fieldset>
    <fieldset>
        <legend><?php echo Yii::t('jacq', 'Living Plant'); ?></legend>
        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0, 'scientific_name_id'); ?>
            <?php echo CHtml::encode($model_livingPlant->id0->scientificName); ?>&nbsp;
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0, 'phenology_id'); ?>
            <?php echo CHtml::encode($model_livingPlant->id0->phenology->phenology); ?>&nbsp;
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0, 'determined_by_id'); ?>
            <?php echo CHtml::encode(($model_livingPlant->id0->determinedBy != null) ? $model_livingPlant->id0->determinedBy->name : ''); ?>&nbsp;
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0, 'habitat'); ?>
            <?php echo CHtml::encode($model_livingPlant->id0->habitat); ?>&nbsp;
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0, 'habitus'); ?>
            <?php echo CHtml::encode($model_livingPlant->id0->habitus); ?>&nbsp;
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0, 'annotation'); ?>
            <?php echo CHtml::encode($model_livingPlant->id0->annotation); ?>&nbsp;
        </div>
        
        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant->id0, 'organisation_id'); ?>
            <?php echo CHtml::encode($model_livingPlant->id0->organisation->description); ?>&nbsp;
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model_livingPlant, 'place_number'); ?>
            <?php echo CHtml::encode($model_livingPlant->place_number); ?>&nbsp;
        </div>

        <div class="row">
            <table style="width: auto;">
                <tr>
                    <td>
                        <?php echo CHtml::activeLabelEx($model_livingPlant, 'accession_number'); ?>
                        <?php echo CHtml::encode($model_livingPlant->accession_number); ?>
                    </td>
                    <td>
                        <?php echo CHtml::activeLabelEx($model_livingPlant, 'ipen_number'); ?>
                        <?php echo CHtml::encode($model_livingPlant->ipen_number); ?>&nbsp;
                    </td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>
