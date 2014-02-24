<?php

/**
 * This is the model class for table "tbl_living_plant".
 *
 * The followings are the available columns in table 'tbl_living_plant':
 * @property integer $id
 * @property integer $accession_number
 * @property string $ipen_number
 * @property integer $ipen_locked
 * @property integer $phyto_control
 * @property string $place_number
 * @property integer $index_seminum
 * @property string $culture_notes
 * @property string $cultivation_date
 * @property integer $index_seminum_type_id
 * @property integer $incoming_date_id
 * @property integer $label_synonym_scientific_name_id
 * @property integer $cultivar_id
 *
 * The followings are the available model relations:
 * @property AlternativeAccessionNumber[] $alternativeAccessionNumbers
 * @property Certificate[] $certificates
 * @property BotanicalObject $id0
 * @property IndexSeminumType $indexSeminumType
 * @property AcquisitionDate $incomingDate
 * @property Cultivar $cultivar
 * @property LivingPlantTreeRecordFilePage[] $livingPlantTreeRecordFilePages
 * @property Relevancy[] $relevancies
 * @property ViewTaxon $labelSynonymViewTaxon
 */
class LivingPlant extends ActiveRecord {
    /**
     * Helper attributes for searching across relations
     */
    public $scientificName_search;
    public $organisation_search;
    public $location_search;
    public $separated_search;
    public $label_type_search;
    
    /**
     * Virtual AccessionNumber Attribute which returns a formatted version of the actual accession_number
     * @return string
     */
    public function getAccessionNumber() {
        return sprintf('%07d', $this->accession_number);
    }
    
    /**
     * Get the synonym for label printing
     * @return string
     */
    public function getLabelSynonymScientificName() {
        // check for a correct scientific name entry
        if( $this->labelSynonymViewTaxon == NULL ) return NULL;
        
        // return the constructed scientific name
        return $this->labelSynonymViewTaxon->getScientificName();
    }
    
    public function init() {
        parent::init();
        
        if( $this->isNewRecord ) {
            $this->ipen_number = "XX-X-XX";
        }
    }
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return LivingPlant the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_living_plant';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id', 'required'),
            array('id, ipen_locked, phyto_control, index_seminum, index_seminum_type_id, incoming_date_id, label_synonym_scientific_name_id, cultivar_id', 'numerical', 'integerOnly' => true),
            array('ipen_number, place_number', 'length', 'max' => 50),
            array('ipenNumberCountryCode', 'length', 'max' => 2),
            array('ipenNumberState', 'length', 'max' => 1),
            array('ipenNumberInstitutionCode', 'length', 'max' => 45),
            array('culture_notes, incoming_date_id', 'safe'),
            array('cultivation_date', 'default', 'setOnEmpty' => true, 'value' => NULL),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('scientificName_search, organisation_search, accession_number, location_search, separated_search, index_seminum, label_type_search, label_synonym_scientific_name_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'alternativeAccessionNumbers' => array(self::HAS_MANY, 'AlternativeAccessionNumber', 'living_plant_id'),
            'certificates' => array(self::HAS_MANY, 'Certificate', 'living_plant_id'),
            'id0' => array(self::BELONGS_TO, 'BotanicalObject', 'id'),
            'indexSeminumType' => array(self::BELONGS_TO, 'IndexSeminumType', 'index_seminum_type_id'),
            'incomingDate' => array(self::BELONGS_TO, 'AcquisitionDate', 'incoming_date_id'),
            'cultivar' => array(self::BELONGS_TO, 'Cultivar', 'cultivar_id'),
            'livingPlantTreeRecordFilePages' => array(self::HAS_MANY, 'LivingPlantTreeRecordFilePage', 'living_plant_id'),
            'relevancies' => array(self::HAS_MANY, 'Relevancy', 'living_plant_id'),
            'labelSynonymViewTaxon' => array(self::BELONGS_TO, 'ViewTaxon', 'label_synonym_scientific_name_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'ipen_number' => Yii::t('jacq', 'IPEN Number'),
            'ipen_locked' => Yii::t('jacq', 'IPEN Locked'),
            'phyto_control' => Yii::t('jacq', 'Phyto Control'),
            'accession_number' => Yii::t('jacq', 'Accession Number'),
            'place_number' => Yii::t('jacq', 'Place Number'),
            'index_seminum' => Yii::t('jacq', 'Index Seminum'),
            'culture_notes' => Yii::t('jacq', 'Culture Notes'),
            'cultivation_date' => Yii::t('jacq', 'Cultivation Date'),
            'index_seminum_type_id' => Yii::t('jacq', 'Index Seminum Type'),
            'incoming_date' => Yii::t('jacq', 'Incoming Date'),
            'scientificName_search' => Yii::t('jacq', 'Scientific Name'),
            'organisation_search' => Yii::t('jacq', 'Garden Site'),
            'location_search' => Yii::t('jacq', 'Location'),
            'separated_search' => Yii::t('jacq', 'Separated' ),
            'labelSynonymScientificName' => Yii::t('jacq', 'Label Synonym'),
            'cultivar_id' => Yii::t('jacq', 'Cultivar'),
            );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // split scientific name search string into components
        $scientificName_searchComponents = explode(' ', $this->scientificName_search);

        $criteria = new CDbCriteria;
        $criteria->with = array('id0', 'id0.organisation', 'id0.acquisitionEvent.location', 'id0.viewTaxon', 'id0.importProperties');
        $criteria->together = true;
        
        // search for scientific name
        $criteria->compare('viewTaxon.genus', $scientificName_searchComponents[0], true);
        if (count($scientificName_searchComponents) >= 2) {
            $criteria->compare('viewTaxon.epithet', $scientificName_searchComponents[1], true);
        }
        // search in imported scientific names
        if(!empty($this->scientificName_search)) {
            $criteria->addCondition("id0.scientific_name_id = " . Yii::app()->params['indetScientificNameId'] . " AND importProperties.species_name LIKE '%" . implode('%', $scientificName_searchComponents) ."%'", "OR");
        }
        
        // add all other search criterias
        $criteria->compare('organisation.description', $this->organisation_search, true);
        $criteria->compare('location.location', $this->location_search, true);
        
        // prepare searching for (alternative) accession numbers
        if( !empty($this->accession_number) ) {
            $accessionNumberCriteria = new CDbCriteria();
            $accessionNumberCriteria->with = array('alternativeAccessionNumbers');
            $accessionNumberCriteria->compare('accession_number', $this->accession_number, true, 'OR');
            $accessionNumberCriteria->compare('alternativeAccessionNumbers.number', $this->accession_number, true, 'OR');

            // add accession number searching to main criteria
            $criteria->with[] = 'alternativeAccessionNumbers';
            $criteria->mergeWith($accessionNumberCriteria, 'AND');
        }
        
        // search for separated entries
        $criteria->compare('id0.separated', $this->separated_search);
        
        // search for index seminum entries
        if( $this->index_seminum == 1 ) {
            $criteria->compare('index_seminum', $this->index_seminum);
        }
        
        // search for label by their types
        if( is_array($this->label_type_search) ) {
            $criteria->with[] = 'id0.tblLabelTypes';
            
            foreach( $this->label_type_search as $label_type ) {
                $criteria->compare('tblLabelTypes.label_type_id', $label_type);
            }
        }
        
        // check if the user is allowed to view plants from the greenhouse
        if( !Yii::app()->user->checkAccess('acs_greenhouse') ) {
            $criteria->compare('organisation.greenhouse',0);
        }
        
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'attributes' => array(
                            'scientificName_search' => array(
                                'asc' => '`herbar_view`.GetScientificName(`id0`.`scientific_name_id`, 0)',
                                'desc' => '`herbar_view`.GetScientificName(`id0`.`scientific_name_id`, 0) DESC'
                            ),
                            'organisation_search' => array(
                                'asc' => 'organisation.description',
                                'desc' => 'organisation.description DESC'
                            ),
                            'location_search' => array(
                                'asc' => 'location.location',
                                'desc' => 'location.location DESC'
                            ),
                            '*'
                        )
                    )
                ));
    }

    /**
     * Set the country code for the IPEN number
     * @param string $value ISO-2 code for the country
     */
    public function setIpenNumberCountryCode($value) {
        if( !$this->ipen_locked ) {
            $this->ipen_number = $value . substr( $this->ipen_number, 2 );
        }
    }

    public function getIpenNumberCountryCode() {
        return substr($this->ipen_number, 0, 2);
    }
    
    public function setIpenNumberState($value) {
        if( !$this->ipen_locked ) {
            $this->ipen_number = substr( $this->ipen_number, 0, 3 ) . $value . substr( $this->ipen_number, 4 );
        }
    }

    public function getIpenNumberState() {
        return substr($this->ipen_number, 3, 1);
    }
    
    public function setIpenNumberInstitutionCode($value) {
        if( !$this->ipen_locked ) {
            $this->ipen_number = substr( $this->ipen_number, 0, 5 ) . $value;
        }
    }

    public function getIpenNumberInstitutionCode() {
        return substr($this->ipen_number, 5);
    }
}
