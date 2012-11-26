<?php

/**
 * This is the model class for table "tbl_living_plant".
 *
 * The followings are the available columns in table 'tbl_living_plant':
 * @property integer $id
 * @property string $ipen_number
 * @property integer $ipen_locked
 * @property integer $phyto_control
 * @property integer $accession_number_id
 * @property string $place_number
 * @property integer $index_seminum
 * @property string $culture_notes
 * @property integer $index_seminum_type_id
 *
 * The followings are the available model relations:
 * @property Certificate[] $certificates
 * @property IpenExternal[] $ipenExternals
 * @property BotanicalObject $id0
 * @property AccessionNumber $accessionNumber
 * @property IndexSeminumType $indexSeminumType
 * @property LivingPlantTreeRecordFilePage[] $livingPlantTreeRecordFilePages
 * @property Relevancy[] $relevancies
 */
class LivingPlant extends CActiveRecord {
    public $scientificName_search;
    public $organisation_search;
    public $accessionNumber_search;
    public $location_search;
    
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
            array('id, accession_number_id', 'required'),
            array('id, ipen_locked, phyto_control, accession_number_id, index_seminum', 'numerical', 'integerOnly' => true),
            array('ipen_number, place_number', 'length', 'max' => 20),
            array('ipenNumberCountryCode', 'length', 'max' => 2),
            array('ipenNumberState', 'length', 'max' => 1),
            array('ipenNumberInstitutionCode', 'length', 'max' => 15),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('scientificName_search, organisation_search, accessionNumber_search, location_search, culture_notes, index_seminum_type_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'certificates' => array(self::HAS_MANY, 'Certificate', 'living_plant_id'),
            'ipenExternals' => array(self::HAS_MANY, 'IpenExternal', 'living_plant_id'),
            'id0' => array(self::BELONGS_TO, 'BotanicalObject', 'id'),
            'accessionNumber' => array(self::BELONGS_TO, 'AccessionNumber', 'accession_number_id'),
            'indexSeminumType' => array(self::BELONGS_TO, 'IndexSeminumType', 'index_seminum_type_id'),
            'livingPlantTreeRecordFilePages' => array(self::HAS_MANY, 'LivingPlantTreeRecordFilePage', 'living_plant_id'),
            'relevancies' => array(self::HAS_MANY, 'Relevancy', 'living_plant_id'),
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
            'accession_number_id' => Yii::t('jacq', 'Accession Number'),
            'place_number' => Yii::t('jacq', 'Place Number'),
            'index_seminum' => Yii::t('jacq', 'Index Seminum'),
            'culture_notes' => Yii::t('jacq', 'Culture Notes'),
            'index_seminum_type_id' => Yii::t('jacq', 'Index Seminum Type'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->with = array('id0', 'accessionNumber', 'id0.organisation', 'id0.acquisitionEvent.location');
        
        $criteria->compare('`herbar_view`.GetScientificName(`id0`.`taxon_id`, 0)', $this->scientificName_search, true);
        $criteria->compare('organisation.description', $this->organisation_search, true);
        $criteria->compare("CONCAT_WS('-',accessionNumber.year,accessionNumber.id,accessionNumber.custom)", $this->accessionNumber_search, true);
        $criteria->compare('location.location', $this->location_search, true);
        
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'attributes' => array(
                            'scientificName_search' => array(
                                'asc' => '`herbar_view`.GetScientificName(`id0`.`taxon_id`, 0)',
                                'desc' => '`herbar_view`.GetScientificName(`id0`.`taxon_id`, 0) DESC'
                            ),
                            'organisation_search' => array(
                                'asc' => 'organisation.description',
                                'desc' => 'organisation.description DESC'
                            ),
                            'accessionNumber_search' => array(
                                'asc' => 'CONCAT_WS('-',accessionNumber.year,accessionNumber.id,accessionNumber.custom)',
                                'desc' => 'CONCAT_WS('-',accessionNumber.year,accessionNumber.id,accessionNumber.custom) DESC'
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
     * Required for automatic logging of changes
     */
    public function behaviors() {
        return array(
            "ActiveRecordLogableBehavior" => 'application.behaviors.ActiveRecordLogableBehavior'
        );
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
