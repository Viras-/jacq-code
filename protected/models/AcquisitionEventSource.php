<?php

/**
 * This is the model class for table "tbl_acquisition_event_source".
 *
 * The followings are the available columns in table 'tbl_acquisition_event_source':
 * @property integer $acquisition_event_source_id
 * @property integer $acquisition_event_id
 * @property integer $acquisition_source_id
 * @property string $source_date
 *
 * The followings are the available model relations:
 * @property AcquisitionEvent $acquisitionEvent
 * @property AcquisitionSource $acquisitionSource
 */
class AcquisitionEventSource extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AcquisitionEventSource the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_acquisition_event_source';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('acquisition_event_id, acquisition_source_id', 'required'),
            array('acquisition_event_id, acquisition_source_id', 'numerical', 'integerOnly' => true),
            array('source_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('acquisition_event_source_id, acquisition_event_id, acquisition_source_id, source_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'acquisitionEvent' => array(self::BELONGS_TO, 'AcquisitionEvent', 'acquisition_event_id'),
            'acquisitionSource' => array(self::BELONGS_TO, 'AcquisitionSource', 'acquisition_source_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'acquisition_event_source_id' => 'Acquisition Event Source',
            'acquisition_event_id' => 'Acquisition Event',
            'acquisition_source_id' => 'Acquisition Source',
            'source_date' => 'Source Date',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('acquisition_event_source_id', $this->acquisition_event_source_id);
        $criteria->compare('acquisition_event_id', $this->acquisition_event_id);
        $criteria->compare('acquisition_source_id', $this->acquisition_source_id);
        $criteria->compare('source_date', $this->source_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
