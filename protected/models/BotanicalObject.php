<?php

/**
 * This is the model class for table "tbl_botanical_object".
 *
 * The followings are the available columns in table 'tbl_botanical_object':
 * @property integer $id
 * @property integer $acquisition_event_id
 * @property integer $separation_id
 * @property integer $phenology_id
 * @property integer $taxon_id
 * @property string $habitat
 * @property string $habitus
 * @property integer $determined_by_id
 * @property string $annotation
 *
 * The followings are the available model relations:
 * @property AcquisitionEvent $acquisitionEvent
 * @property Separation $separation
 * @property Phenology $phenology
 * @property Diaspora $diaspora
 * @property Image[] $images
 * @property LivingPlant $livingPlant
 * @property ObjectSex[] $objectSexes
 */
class BotanicalObject extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BotanicalObject the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_botanical_object';
    }

    /**
     * Return connection to herbar_view database
     * @return CDbConnection 
     */
    private function getDbHerbarView() {
        return Yii::app()->dbHerbarView;
    }

    /**
     * Return connection to herbarinput database
     * @return CDbConnection 
     */
    private function getDbHerbarInput() {
        return Yii::app()->dbHerbarInput;
    }

    /**
     * Fetch the scientific name for the given botanical object
     * @return string 
     */
    public function getScientificName() {
        if ($this->taxon_id <= 0)
            return NULL;

        $dbHerbarView = $this->getDbHerbarView();
        $command = $dbHerbarView->createCommand("SELECT GetScientificName( " . $this->taxon_id . ", 0 ) AS 'ScientificName'");
        $scientificNames = $command->queryAll();

        return $scientificNames[0]['ScientificName'];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('acquisition_event_id, taxon_id', 'required'),
            array('acquisition_event_id, separation_id, phenology_id, taxon_id, determined_by_id', 'numerical', 'integerOnly' => true),
            array('habitat, habitus', 'length', 'max' => 45),
            array('annotation', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, acquisition_event_id, separation_id, phenology_id, taxon_id, habitat, habitus, determined_by_id, annotation', 'safe', 'on' => 'search'),
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
            'separation' => array(self::BELONGS_TO, 'Separation', 'separation_id'),
            'phenology' => array(self::BELONGS_TO, 'Phenology', 'phenology_id'),
            'diaspora' => array(self::HAS_ONE, 'Diaspora', 'id'),
            'images' => array(self::HAS_MANY, 'Image', 'botanical_object_id'),
            'livingPlant' => array(self::HAS_ONE, 'LivingPlant', 'id'),
            'objectSexes' => array(self::HAS_MANY, 'ObjectSex', 'object_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'acquisition_event_id' => 'Acquisition Event',
            'separation_id' => 'Separation',
            'phenology_id' => 'Phenology',
            'taxon_id' => 'Taxon',
            'habitat' => 'Habitat',
            'habitus' => 'Habitus',
            'determined_by_id' => 'Determined By',
            'annotation' => 'Annotation',
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
        $criteria->compare('acquisition_event_id', $this->acquisition_event_id);
        $criteria->compare('separation_id', $this->separation_id);
        $criteria->compare('phenology_id', $this->phenology_id);
        $criteria->compare('taxon_id', $this->taxon_id);
        $criteria->compare('habitat', $this->habitat, true);
        $criteria->compare('habitus', $this->habitus, true);
        $criteria->compare('determined_by_id', $this->determined_by_id);
        $criteria->compare('annotation', $this->annotation, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Return the name of the agent
     * @return string Name of agent
     */
    public function getDeterminedByName() {
        if( $this->determined_by_id <= 0 ) return NULL;
        
        // We fetch the agent name from a different database
        $dbHerbarInput = $this->getDbHerbarInput();
        $command = $dbHerbarInput->createCommand()
                ->select("Sammler")
                ->from("tbl_collector")
                ->where('SammlerID = :SammlerID', array(':SammlerID' => $this->determined_by_id));
        $rows = $command->queryAll();
        
        // Check if we found something
        if( count($rows) <= 0 ) return NULL;
        
        // Return agent name
        return $rows[0]['Sammler'];
    }
}