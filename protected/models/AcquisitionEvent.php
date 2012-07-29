<?php

/**
 * This is the model class for table "tbl_acquisition_event".
 *
 * The followings are the available columns in table 'tbl_acquisition_event':
 * @property integer $id
 * @property integer $agent_id
 * @property integer $acquisition_date_id
 * @property integer $acquisition_type_id
 * @property string $number
 * @property integer $location_id
 *
 * The followings are the available model relations:
 * @property AcquisitionDate $acquisitionDate
 * @property AcquisitionType $acquisitionType
 * @property Location $location
 * @property BotanicalObject[] $botanicalObjects
 */
class AcquisitionEvent extends CActiveRecord {
    /**
     * Return connection to herbarinput database
     * @return CDbConnection 
     */
    private function getDbHerbarInput() {
        return Yii::app()->dbHerbarInput;
    }

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
            array('acquisition_date_id, acquisition_type_id, location_id', 'required'),
            array('agent_id, acquisition_date_id, acquisition_type_id, location_id', 'numerical', 'integerOnly' => true),
            array('number', 'length', 'max' => 45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, agent_id, acquisition_date_id, acquisition_type_id, number, location_id', 'safe', 'on' => 'search'),
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
            'botanicalObjects' => array(self::HAS_MANY, 'BotanicalObject', 'acquisition_event_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'agent_id' => 'Agent',
            'acquisition_date_id' => 'Acquisition Date',
            'acquisition_type_id' => 'Acquisition Type',
            'number' => 'Number',
            'location_id' => 'Location',
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
        $criteria->compare('agent_id', $this->agent_id);
        $criteria->compare('acquisition_date_id', $this->acquisition_date_id);
        $criteria->compare('acquisition_type_id', $this->acquisition_type_id);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('location_id', $this->location_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Return the name of the agent
     * @return string Name of agent
     */
    public function getAgentName() {
        if( $this->agent_id <= 0 ) return NULL;
        
        // We fetch the agent name from a different database
        $dbHerbarInput = $this->getDbHerbarInput();
        $command = $dbHerbarInput->createCommand()
                ->select("Sammler")
                ->from("tbl_collector")
                ->where('SammlerID = :SammlerID', array(':SammlerID' => $this->agent_id));
        $rows = $command->queryAll();
        
        // Check if we found something
        if( count($rows) <= 0 ) return NULL;
        
        // Return agent name
        return $rows[0]['Sammler'];
    }
}
