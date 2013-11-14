<?php

/**
 * This is the model class for table "tbl_botanical_object".
 *
 * The followings are the available columns in table 'tbl_botanical_object':
 * @property integer $id
 * @property integer $acquisition_event_id
 * @property integer $phenology_id
 * @property integer $scientific_name_id
 * @property integer $determined_by_id
 * @property string $determination_date
 * @property string $habitat
 * @property string $habitus
 * @property string $annotation
 * @property string $recording_date
 * @property integer $organisation_id
 * @property integer $accessible
 * @property integer $redetermine
 * @property integer $ident_status_id
 * @property integer $separated
 * @property string $scientificName
 *
 * The followings are the available model relations:
 * @property AcquisitionEvent $acquisitionEvent
 * @property Phenology $phenology
 * @property Person $determinedBy
 * @property Organisation $organisation
 * @property IdentStatus $identStatus
 * @property LabelType[] $tblLabelTypes
 * @property ScientificNameInformation $scientificNameInformation
 * @property BotanicalObjectSex[] $botanicalObjectSexes
 * @property Diaspora $diaspora
 * @property Image[] $images
 * @property ImportProperties $importProperties
 * @property LivingPlant $livingPlant
 * @property Separation[] $separations
 * @property ViewTaxon $viewTaxon
 */
class BotanicalObject extends ActiveRecord {

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
            $this->recording_date = date('Y-m-d h:i:s');
        }
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_botanical_object';
    }

    /**
     * Virtual attribute for CSV export
     * @return string
     */
    public function getCSVSexes() {
        $csvSexes = array();
        
        if( is_array($this->botanicalObjectSexes) ) {
            foreach( $this->botanicalObjectSexes as $BotanicalObjectSex ) {
                $csvSexes[] = Yii::t('jacq', $BotanicalObjectSex->sex);
            }
        }
        
        return join(',', $csvSexes);
    }

    /**
     * Fetch the scientific name for the given botanical object
     * @return string 
     */
    public function getScientificName() {
        if( $this->viewTaxon == NULL ) return NULL;
        
        return $this->viewTaxon->getScientificName();
    }
    
    /**
     * Getter function for the family of the currently assigned scientific name
     * @return string Name of family
     */
    public function getFamily() {
        $model_familyTaxSynonymy = $this->getFamilyByReference(Yii::app()->params['familyClassificationId'], 'citation');
        
        // if no family was found, return 'Unknown'
        if( $model_familyTaxSynonymy == NULL ) return Yii::t('jacq', 'Unknown');
        
        // Otherwise return the scientific name
        return $model_familyTaxSynonymy->viewTaxon->getScientificName();
    }
    
    /**
     * Get the family entry for the currently assigned scientific name
     * @param int $reference_id ID of reference to use for classification
     * @param string $reference_type Type of reference (currently only citation is supported)
     * @return \TaxSynonymy|NULL Either the TaxSynonymy entry for the family, or NULL if none found
     */
    protected function getFamilyByReference($reference_id, $reference_type = 'citation') {
        $reference_id = intval($reference_id);
        
        if( $reference_id <= 0 || !in_array($reference_type, array('citation')) ) {
            return NULL;
        }
        
        // try to find the synonymy entry
        $model_taxSynonymy = TaxSynonymy::model()->findByAttributes(array(
            'taxonID' => $this->scientific_name_id,
            'source_citationID' => $reference_id
        ));
        
        // check if we found a valid entry
        if( $model_taxSynonymy == NULL ) return NULL;
        
        // make sure we have the accepted entry
        $model_taxSynonymy = $model_taxSynonymy->getAccepted();
        
        // get the family entry from synonymy
        return $model_taxSynonymy->getFamily();
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('acquisition_event_id, scientific_name_id, recording_date', 'required'),
            array('acquisition_event_id, phenology_id, scientific_name_id, determined_by_id, organisation_id, accessible, redetermine, ident_status_id, separated', 'numerical', 'integerOnly' => true),
            array('habitat, habitus', 'length', 'max' => 45),
            array('determination_date, annotation', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, acquisition_event_id, phenology_id, scientific_name_id, habitat, habitus, determined_by_id, annotation, botanicalObjectSexes, redetermine, ident_status_id, separated', 'safe', 'on' => 'search'),
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
            'importProperties' => array(self::HAS_ONE, 'ImportProperties', 'botanical_object_id'),
            'livingPlant' => array(self::HAS_ONE, 'LivingPlant', 'id'),
            'organisation' => array(self::BELONGS_TO, 'Organisation', 'organisation_id'),
            'identStatus' => array(self::BELONGS_TO, 'IdentStatus', 'ident_status_id'),
            'tblLabelTypes' => array(self::MANY_MANY, 'LabelType', 'tbl_botanical_object_label(botanical_object_id, label_type_id)'),
            'scientificNameInformation' => array(self::BELONGS_TO, 'ScientificNameInformation', 'scientific_name_id'),
            'separations' => array(self::HAS_MANY, 'Separation', 'botanical_object_id'),
            'viewTaxon' => array(self::BELONGS_TO, 'ViewTaxon', 'scientific_name_id'),
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
            'scientific_name_id' => Yii::t('jacq', 'Scientific Name'),
            'habitat' => Yii::t('jacq', 'Habitat'),
            'habitus' => Yii::t('jacq', 'Habitus'),
            'determined_by_id' => Yii::t('jacq', 'Determined By'),
            'determination_date' => Yii::t('jacq', 'Determination Date'),
            'annotation' => Yii::t('jacq', 'Annotation'),
            'recording_date' => Yii::t('jacq', 'Recording Date'),
            'organisation_id' => Yii::t('jacq', 'Garden Site'),
            'accessible' => Yii::t('jacq', 'Accessible'),
            'redetermine' => Yii::t('jacq', 'Redetermine'),
            'ident_status_id' => Yii::t('jacq', 'Ident Status'),
            'separated' => Yii::t('jacq', 'Separated'),
            'family' => Yii::t('jacq', 'Family'),
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
        $criteria->compare('scientific_name_id', $this->scientific_name_id);
        $criteria->compare('determined_by_id', $this->determined_by_id);
        $criteria->compare('determination_date', $this->determination_date, true);
        $criteria->compare('habitat', $this->habitat, true);
        $criteria->compare('habitus', $this->habitus, true);
        $criteria->compare('annotation', $this->annotation, true);
        $criteria->compare('recording_date', $this->recording_date, true);
        $criteria->compare('organisation_id', $this->organisation_id);
        $criteria->compare('accessible', $this->accessible);
        $criteria->compare('redetermine', $this->redetermine);
        $criteria->compare('ident_status_id', $this->ident_status_id);
        $criteria->compare('separated', $this->separated);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
