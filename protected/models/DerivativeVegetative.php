<?php

/**
 * This is the model class for table "tbl_derivative_vegetative".
 *
 * The followings are the available columns in table 'tbl_derivative_vegetative':
 * @property integer $derivative_vegetative_id
 * @property integer $living_plant_id
 * @property integer $accesion_number
 * @property integer $organisation_id
 * @property integer $phenology_id
 * @property string $cultivation_date
 * @property string $annotation
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
            array('living_plant_id, accesion_number, organisation_id, phenology_id', 'required'),
            array('living_plant_id, accesion_number, organisation_id, phenology_id', 'numerical', 'integerOnly' => true),
            array('cultivation_date, annotation', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('derivative_vegetative_id, living_plant_id, accesion_number, organisation_id, phenology_id, cultivation_date, annotation', 'safe', 'on' => 'search'),
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
            'derivative_vegetative_id' => 'Derivative Vegetative',
            'living_plant_id' => 'Living Plant',
            'accesion_number' => 'Accesion Number',
            'organisation_id' => 'Organisation',
            'phenology_id' => 'Phenology',
            'cultivation_date' => 'Cultivation Date',
            'annotation' => 'Annotation',
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
        $criteria->compare('accesion_number', $this->accesion_number);
        $criteria->compare('organisation_id', $this->organisation_id);
        $criteria->compare('phenology_id', $this->phenology_id);
        $criteria->compare('cultivation_date', $this->cultivation_date, true);
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
