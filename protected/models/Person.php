<?php

/**
 * This is the model class for table "tbl_person".
 *
 * The followings are the available columns in table 'tbl_person':
 * @property integer $id
 * @property string $name
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
     * Return a person entry by name, automatically adds a new one if it does not find any
     * @param string $name Name to search for
     * @return Person
     */
    public static function getByName($name) {
        return Person::getByAttributes($name);
    }

    /**
     * Get a person by its attributes, but use like condition
     * @param string $name Name of person to search for
     * @return null|\Person
     */
    private static function getByAttributes($name = '') {
        if (empty($name))
            return NULL;

        // create search criteria
        $dbCriteria = new CDbCriteria();
        if (!empty($name)) {
            $dbCriteria->compare('name', $name);
        }

        // Find fitting entry
        $model_person = Person::model()->find($dbCriteria);
        // If none found, add a new one
        if ($model_person == null) {
            $model_person = new Person;
            $model_person->name = $name;
            $model_person->save();
        }

        // Finally return the model
        return $model_person;
    }

}
