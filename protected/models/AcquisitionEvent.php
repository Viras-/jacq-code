<?php

/**
 * This is the model class for table "tbl_acquisition_event".
 *
 * The followings are the available columns in table 'tbl_acquisition_event':
 * @property integer $id
 * @property integer $acquisition_date_id
 * @property integer $acquisition_type_id
 * @property integer $location_id
 * @property string $number
 * @property integer $altitude_min
 * @property integer $altitude_max
 * @property integer $exactness
 * @property integer $latitude_degrees
 * @property integer $latitude_minutes
 * @property integer $latitude_seconds
 * @property string $latitude_half
 * @property integer $longitude_degrees
 * @property integer $longitude_minutes
 * @property integer $longitude_seconds
 * @property string $longitude_half
 *
 * The followings are the available model relations:
 * @property AcquisitionDate $acquisitionDate
 * @property AcquisitionType $acquisitionType
 * @property Location $location
 * @property Person[] $tblPeople
 * @property BotanicalObject[] $botanicalObjects
 */
class AcquisitionEvent extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AcquisitionEvent the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_acquisition_event';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('acquisition_date_id, acquisition_type_id', 'required'),
            array('acquisition_date_id, acquisition_type_id, location_id, altitude_min, altitude_max, exactness, latitude_degrees, latitude_minutes, latitude_seconds, longitude_degrees, longitude_minutes, longitude_seconds', 'numerical', 'integerOnly' => true),
            array('latitude_half, longitude_half', 'length', 'max'=>1),
            array('number', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, acquisition_date_id, acquisition_type_id, location_id, number, altitude_min, altitude_max, exactness', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'acquisitionDate' => array(self::BELONGS_TO, 'AcquisitionDate', 'acquisition_date_id'),
            'acquisitionType' => array(self::BELONGS_TO, 'AcquisitionType', 'acquisition_type_id'),
            'location' => array(self::BELONGS_TO, 'Location', 'location_id'),
            'tblPeople' => array(self::MANY_MANY, 'Person', 'tbl_acquisition_event_person(acquisition_event_id, person_id)'),
            'botanicalObjects' => array(self::HAS_MANY, 'BotanicalObject', 'acquisition_event_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'acquisition_date_id' => Yii::t('jacq', 'Acquisition Date'),
            'acquisition_type_id' => Yii::t('jacq', 'Acquisition Type'),
            'location_id' => Yii::t('jacq', 'Location'),
            'number' => Yii::t('jacq', 'Number'),
            'altitude_min' => Yii::t('jacq', 'Altitude Min'),
            'altitude_max' => Yii::t('jacq', 'Altitude Max'),
            'exactness' => Yii::t('jacq', 'Exactness'),
            'latitude_degree' => Yii::t('jacq', 'Latitude'),
            'longitude_degree' => Yii::t('jacq', 'Longitude'),
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

        $criteria->compare('id', $this->id);
        $criteria->compare('acquisition_date_id', $this->acquisition_date_id);
        $criteria->compare('acquisition_type_id', $this->acquisition_type_id);
        $criteria->compare('location_id', $this->location_id);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('altitude_min', $this->altitude_min);
        $criteria->compare('altitude_max', $this->altitude_max);
        $criteria->compare('exactness', $this->exactness);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
}
