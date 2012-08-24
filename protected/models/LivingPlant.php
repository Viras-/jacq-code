<?php

/**
 * This is the model class for table "tbl_living_plant".
 *
 * The followings are the available columns in table 'tbl_living_plant':
 * @property integer $id
 * @property string $ipen_number
 * @property integer $phyto_control
 * @property string $phyto_sanitary_product_number
 * @property integer $accession_number_id
 * @property string $place_number
 *
 * The followings are the available model relations:
 * @property CitesNumber[] $citesNumbers
 * @property BotanicalObject $id0
 * @property AccessionNumber $accessionNumber
 * @property LivingPlantTreeRecordFilePage[] $livingPlantTreeRecordFilePages
 * @property Relevancy[] $relevancies
 */
class LivingPlant extends CActiveRecord {
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
            array('id, phyto_control, accession_number_id', 'numerical', 'integerOnly' => true),
            array('ipen_number, phyto_sanitary_product_number, place_number', 'length', 'max' => 20),
            array('ipenNumberCountryCode', 'length', 'max' => 2),
            array('ipenNumberState', 'length', 'max' => 1),
            array('ipenNumberInstitutionCode', 'length', 'max' => 15),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, ipen_number, phyto_control, phyto_sanitary_product_number, accession_number_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'citesNumbers' => array(self::HAS_MANY, 'CitesNumber', 'living_plant_id'),
            'id0' => array(self::BELONGS_TO, 'BotanicalObject', 'id'),
            'accessionNumber' => array(self::BELONGS_TO, 'AccessionNumber', 'accession_number_id'),
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
            'ipen_number' => Yii::t('jacq', 'Ipen Number'),
            'phyto_control' => Yii::t('jacq', 'Phyto Control'),
            'phyto_sanitary_product_number' => Yii::t('jacq', 'Phyto Sanitary Product Number'),
            'accession_number_id' => Yii::t('jacq', 'Accession Number'),
            'place_number' => Yii::t('jacq', 'Place Number')
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
        $criteria->compare('ipen_number', $this->ipen_number, true);
        $criteria->compare('phyto_control', $this->phyto_control);
        $criteria->compare('phyto_sanitary_product_number', $this->phyto_sanitary_product_number, true);
        $criteria->compare('accession_number_id', $this->accession_number_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    
    public function setIpenNumberCountryCode($value) {
        error_log( 'setIpenNumberCountryCode: ' . $value );
        
        $this->ipen_number = $value . substr( $this->ipen_number, 2 );
    }

    public function getIpenNumberCountryCode() {
        return substr($this->ipen_number, 0, 2);
    }
    
    public function setIpenNumberState($value) {
        $this->ipen_number = substr( $this->ipen_number, 0, 3 ) . $value . substr( $this->ipen_number, 4 );
    }

    public function getIpenNumberState() {
        return substr($this->ipen_number, 3, 1);
    }
    
    public function setIpenNumberInstitutionCode($value) {
        $this->ipen_number = substr( $this->ipen_number, 0, 5 ) . $value;
    }

    public function getIpenNumberInstitutionCode() {
        return substr($this->ipen_number, 5);
    }
    
/*
    public function __set($name, $value) {
        error_log('__set: ' . $name . ' / ' . $value);
        
        switch($name) {
            case 'ipen_number_countryCode':
                $this->ipen_number = $value . substr( $this->ipen_number, 2 );
                break;
            case 'ipen_number_state':
                $this->ipen_number = substr( $this->ipen_number, 0, 3 ) . $value . substr( $this->ipen_number, 4 );
                break;
            case 'ipen_number_institutionCode':
                $this->ipen_number = substr( $this->ipen_number, 0, 5 ) . $value;
                break;
            default:
                parent::__set($name, $value);
        }
        
        error_log('ipen_number: ' . $this->ipen_number);
    }
    
    public function __get($name) {
        switch( $name ) {
            case 'ipen_number_countryCode':
                return substr($this->ipen_number, 0, 2);
                break;
            case 'ipen_number_state':
                return substr($this->ipen_number, 3, 1);
                break;
            case 'ipen_number_institutionCode':
                return substr($this->ipen_number, 5);
                break;
            default:
                return parent::__get($name);
                break;
        }
    }*/
}
