<?php

/**
 * This is the model class for table "tbl_derivative_vegetative".
 *
 * The followings are the available columns in table 'tbl_derivative_vegetative':
 * @property integer $derivative_vegetative_id
 * @property integer $living_plant_id
 * @property integer $accession_number
 * @property integer $organisation_id
 * @property integer $phenology_id
 * @property string $cultivation_date
 * @property integer $index_seminum
 * @property string $annotation
 * @property string $place_number
 * @property integer $separated
 *
 * The followings are the available model relations:
 * @property LivingPlant $livingPlant
 * @property Organisation $organisation
 * @property Phenology $phenology
 * @property Separation[] $separations
 */
class DerivativeVegetative extends CActiveRecord {

    /**
     * @var helper attribute for deleting a vegetative entry
     */
    public $delete = 0;

    /**
     * Virtual AccessionNumber Attribute which returns a formatted version of the combined accession number
     * @return string
     */
    public function getAccessionNumber() {
        if ($this->derivative_vegetative_id <= 0) {
            return '';
        }

        return $this->livingPlant->accessionNUmber . sprintf('-%03d', $this->accession_number);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_derivative_vegetative';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('living_plant_id, organisation_id, phenology_id, index_seminum, separated', 'required'),
            array('living_plant_id, accession_number, organisation_id, phenology_id, index_seminum, separated', 'numerical', 'integerOnly' => true),
            array('cultivation_date, annotation', 'safe'),
            array('place_number', 'length', 'max' => 20),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('derivative_vegetative_id, living_plant_id, accsesion_number, organisation_id, phenology_id, cultivation_date, annotation', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'livingPlant' => array(self::BELONGS_TO, 'LivingPlant', 'living_plant_id'),
            'organisation' => array(self::BELONGS_TO, 'Organisation', 'organisation_id'),
            'phenology' => array(self::BELONGS_TO, 'Phenology', 'phenology_id'),
            'separations' => array(self::HAS_MANY, 'Separation', 'derivative_vegetative_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'derivative_vegetative_id' => Yii::t('jacq', 'Derivative Vegetative'),
            'living_plant_id' => Yii::t('jacq', 'Living Plant'),
            'accession_number' => Yii::t('jacq', 'Accession Number'),
            'organisation_id' => Yii::t('jacq', 'Organisation'),
            'phenology_id' => Yii::t('jacq', 'Phenology'),
            'cultivation_date' => Yii::t('jacq', 'Cultivation Date'),
            'index_seminum' => Yii::t('jacq', 'Index Seminum'),
            'annotation' => Yii::t('jacq', 'Annotation'),
            'place_number' => Yii::t('jacq', 'Place Number'),
            'separated' => Yii::t('jacq', 'Separated'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('derivative_vegetative_id', $this->derivative_vegetative_id);
        $criteria->compare('living_plant_id', $this->living_plant_id);
        $criteria->compare('accession_number', $this->accession_number);
        $criteria->compare('organisation_id', $this->organisation_id);
        $criteria->compare('phenology_id', $this->phenology_id);
        $criteria->compare('cultivation_date', $this->cultivation_date, true);
        $criteria->compare('index_seminum', $this->index_seminum);
        $criteria->compare('annotation', $this->annotation, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DerivativeVegetative the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
