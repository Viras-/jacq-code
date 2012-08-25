<?php

/**
 * This is the model class for table "tbl_location".
 *
 * The followings are the available columns in table 'tbl_location':
 * @property integer $id
 * @property string $location
 *
 * The followings are the available model relations:
 * @property AcquisitionEvent[] $acquisitionEvents
 * @property LocationGeonames $locationGeonames
 */
class Location extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Location the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_location';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('location', 'required'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, location', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'acquisitionEvents' => array(self::HAS_MANY, 'AcquisitionEvent', 'location_id'),
            'locationGeonames' => array(self::HAS_ONE, 'LocationGeonames', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'location' => Yii::t('jacq', 'Location'),
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
        $criteria->compare('location', $this->location, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Return a location entry by name, automatically adds a new one if it does not find any
     * @param string $name Name of location to look for
     * @return \Location 
     */
    public static function getByName($name) {
        $model_location = Location::model()->findByAttributes(array("location" => $name));
        if ($model_location == NULL) {
            $model_location = new Location;
            $model_location->location = $name;
            $model_location->save();
        }

        return $model_location;
    }
}
