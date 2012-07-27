<?php

/**
 * This is the model class for table "tbl_living_plant".
 *
 * The followings are the available columns in table 'tbl_living_plant':
 * @property integer $id
 * @property integer $garden_site_id
 * @property string $ipen_number
 * @property integer $phyto_control
 * @property string $phyto_sanitary_product_number
 * @property integer $accession_number_id
 *
 * The followings are the available model relations:
 * @property CitesNumber[] $citesNumbers
 * @property BotanicalObject $id0
 * @property GardenSite $gardenSite
 * @property AccessionNumber $accessionNumber
 * @property LivingPlantTreeRecordFilePage[] $livingPlantTreeRecordFilePages
 * @property Relevancy[] $relevancies
 */
class LivingPlant extends CActiveRecord {

    /**
     * Setup default values during init 
     */
    public function init() {
        parent::init();

        if ($this->isNewRecord) {
            $this->ipen_number = "XX-X-WU";
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
            array('id, garden_site_id, phyto_control, accession_number_id', 'numerical', 'integerOnly' => true),
            array('ipen_number, phyto_sanitary_product_number', 'length', 'max' => 20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, garden_site_id, ipen_number, phyto_control, phyto_sanitary_product_number, accession_number_id', 'safe', 'on' => 'search'),
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
            'gardenSite' => array(self::BELONGS_TO, 'GardenSite', 'garden_site_id'),
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
            'id' => 'ID',
            'garden_site_id' => 'Garden Site',
            'ipen_number' => 'Ipen Number',
            'phyto_control' => 'Phyto Control',
            'phyto_sanitary_product_number' => 'Phyto Sanitary Product Number',
            'accession_number_id' => 'Accession Number',
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
        $criteria->compare('garden_site_id', $this->garden_site_id);
        $criteria->compare('ipen_number', $this->ipen_number, true);
        $criteria->compare('phyto_control', $this->phyto_control);
        $criteria->compare('phyto_sanitary_product_number', $this->phyto_sanitary_product_number, true);
        $criteria->compare('accession_number_id', $this->accession_number_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}