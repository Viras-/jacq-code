<?php

/**
 * This is the model class for table "tbl_location_coordinates".
 *
 * The followings are the available columns in table 'tbl_location_coordinates':
 * @property integer $id
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
 * @property AcquisitionEvent[] $acquisitionEvents
 */
class LocationCoordinates extends CActiveRecord {
    private static $sexagesimal_pattern = "%3d° %2d′ %2d″";

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return LocationCoordinates the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_location_coordinates';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('altitude_min, altitude_max, exactness, latitude_degrees, latitude_minutes, latitude_seconds, longitude_degrees, longitude_minutes, longitude_seconds', 'numerical', 'integerOnly' => true),
            array('latitude_half, longitude_half', 'length', 'max' => 1),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, altitude_min, altitude_max, exactness, latitude_degrees, latitude_minutes, latitude_seconds, latitude_half, longitude_degrees, longitude_minutes, longitude_seconds, longitude_half', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'acquisitionEvents' => array(self::HAS_MANY, 'AcquisitionEvent', 'location_coordinates_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'altitude_min' => 'Altitude Min',
            'altitude_max' => 'Altitude Max',
            'exactness' => 'Exactness',
            'latitude_degrees' => 'Latitude Degrees',
            'latitude_minutes' => 'Latitude Minutes',
            'latitude_seconds' => 'Latitude Seconds',
            'latitude_half' => 'Latitude Half',
            'longitude_degrees' => 'Longitude Degrees',
            'longitude_minutes' => 'Longitude Minutes',
            'longitude_seconds' => 'Longitude Seconds',
            'longitude_half' => 'Longitude Half',
            'altitude' => Yii::t('jacq', 'Altitude'),
            'exactness' => Yii::t('jacq', 'Exactness'),
            'latitude' => Yii::t('jacq', 'Latitude'),
            'longitude' => Yii::t('jacq', 'Longitude'),
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
        $criteria->compare('altitude_min', $this->altitude_min);
        $criteria->compare('altitude_max', $this->altitude_max);
        $criteria->compare('exactness', $this->exactness);
        $criteria->compare('latitude_degrees', $this->latitude_degrees);
        $criteria->compare('latitude_minutes', $this->latitude_minutes);
        $criteria->compare('latitude_seconds', $this->latitude_seconds);
        $criteria->compare('latitude_half', $this->latitude_half, true);
        $criteria->compare('longitude_degrees', $this->longitude_degrees);
        $criteria->compare('longitude_minutes', $this->longitude_minutes);
        $criteria->compare('longitude_seconds', $this->longitude_seconds);
        $criteria->compare('longitude_half', $this->longitude_half, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Helper function for return the latitude coordinates in Sexagesimal writing
     * @return string
     */
    public function getLatitudeSexagesimal() {
        return sprintf(self::$sexagesimal_pattern, $this->latitude_degrees, $this->latitude_minutes, $this->latitude_seconds);
    }

    /**
     * Helper function for return the latitude coordinates in Sexagesimal writing
     * @return string
     */
    public function getLongitudeSexagesimal() {
        return sprintf(self::$sexagesimal_pattern, $this->longitude_degrees, $this->longitude_minutes, $this->longitude_seconds);
    }
}
