<?php

/**
 * This is the model class for table "tbl_botanical_object".
 *
 * The followings are the available columns in table 'tbl_botanical_object':
 * @property integer $id
 * @property integer $acquisition_event_id
 * @property integer $phenology_id
 * @property integer $taxon_id
 * @property integer $determined_by_id
 * @property string $habitat
 * @property string $habitus
 * @property string $annotation
 * @property string $recording_date
 * @property integer $organisation_id
 *
 * The followings are the available model relations:
 * @property AcquisitionEvent $acquisitionEvent
 * @property Phenology $phenology
 * @property Person $determinedBy
 * @property Organisation $organisation
 * @property BotanicalObjectSex[] $botanicalObjectSexes
 * @property Diaspora $diaspora
 * @property Image[] $images
 * @property LivingPlant $livingPlant
 * @property Separation[] $separations
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

    public function init() {
        parent::init();

        if ($this->isNewRecord) {
            $this->recording_date = date('Y-m-d');
        }
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_botanical_object';
    }

    /**
     * Wrapper for getting the virtual scientificName attribute
     * @param string $name Name of attribute to get
     * @return mixed value of attribute 
     */
    public function __get($name) {
        if ($name == "scientificName") {
            return $this->getScientificName();
        }
        else {
            return parent::__get($name);
        }
    }

    /**
     * Return connection to herbar_view database
     * @return CDbConnection 
     */
    private function getDbHerbarView() {
        return Yii::app()->dbHerbarView;
    }

    /**
     * Fetch the scientific name for the given botanical object
     * @return string 
     */
    private function getScientificName() {
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
            array('acquisition_event_id, taxon_id, recording_date', 'required'),
            array('acquisition_event_id, phenology_id, taxon_id, determined_by_id, organisation_id', 'numerical', 'integerOnly' => true),
            array('habitat, habitus', 'length', 'max' => 45),
            array('annotation', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, acquisition_event_id, phenology_id, taxon_id, habitat, habitus, determined_by_id, annotation', 'safe', 'on' => 'search'),
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
            'phenology' => array(self::BELONGS_TO, 'Phenology', 'phenology_id'),
            'determinedBy' => array(self::BELONGS_TO, 'Person', 'determined_by_id'),
            'botanicalObjectSexes' => array(self::HAS_MANY, 'BotanicalObjectSex', 'botanical_object_id'),
            'diaspora' => array(self::HAS_ONE, 'Diaspora', 'id'),
            'images' => array(self::HAS_MANY, 'Image', 'botanical_object_id'),
            'livingPlant' => array(self::HAS_ONE, 'LivingPlant', 'id'),
            'organisation' => array(self::BELONGS_TO, 'Organisation', 'organisation_id'),
            'separations' => array(self::HAS_MANY, 'Separation', 'botanical_object_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'acquisition_event_id' => Yii::t('jacq', 'Acquisition Event'),
            'phenology_id' => Yii::t('jacq', 'Phenology'),
            'taxon_id' => Yii::t('jacq', 'Taxon'),
            'habitat' => Yii::t('jacq', 'Habitat'),
            'habitus' => Yii::t('jacq', 'Habitus'),
            'determined_by_id' => Yii::t('jacq', 'Determined By'),
            'annotation' => Yii::t('jacq', 'Annotation'),
            'recording_date' => Yii::t('jacq', 'Recording Date'),
            'organisation_id' => Yii::t('jacq', 'Garden Site'),
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
     * Required for automatic logging of changes
     */
    public function behaviors() {
        return array(
            "ActiveRecordLogableBehavior" => 'application.behaviors.ActiveRecordLogableBehavior'
        );
    }
}
