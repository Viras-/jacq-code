<?php

/**
 * This is the model class for table "tbl_person".
 *
 * The followings are the available columns in table 'tbl_person':
 * @property integer $id
 * @property string $name
 * @property string $collNr
 *
 * The followings are the available model relations:
 * @property AcquisitionEvent[] $acquisitionEvents
 * @property BotanicalObject[] $botanicalObjects
 */
class Person extends ActiveRecord {
    /**
     * @var helper attribute for deleting a person entry
     */
    public $delete = 0;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Person the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_person';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 255),
            array('collNr', 'length', 'max'=>45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'acquisitionEvents' => array(self::HAS_MANY, 'AcquisitionEvent', 'agent_id'),
            'botanicalObjects' => array(self::HAS_MANY, 'BotanicalObject', 'determined_by_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'collNr' => 'Coll Nr',
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
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    
    /**
     * Find a person as a collector
     * @param string $name Name of collector
     * @param string $collNr Collector number (optional)
     * @return \Person
     */
    public static function getCollector( $name, $collNr = '' ) {
        if( empty($name) ) return NULL;
        
        // Find fitting entry
        $model_person = Person::model()->findByAttributes(array("name" => $name, 'collNr' => $collNr));
        // If none found, add a new one
        if( $model_person == null ) {
            $model_person = new Person;
            $model_person->name = $name;
            $model_person->collNr = $collNr;
            $model_person->save();
        }
        
        // Finally return the model
        return $model_person;
    }

    /**
     * Return a person entry by name, automatically adds a new one if it does not find any
     * @param string $name Name to search for
     * @return Person 
     */
    public static function getByName( $name ) {
        // Find fitting entry
        $model_person = Person::model()->findByAttributes(array("name" => $name));
        // If none found, add a new one
        if( $model_person == null ) {
            $model_person = new Person;
            $model_person->name = $name;
            $model_person->save();
        }
        
        // Finally return the model
        return $model_person;
    }
}
