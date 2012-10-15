<?php

/**
 * This is the model class for table "tbl_accession_number".
 *
 * The followings are the available columns in table 'tbl_accession_number':
 * @property integer $id
 * @property integer $year
 * @property integer $individual
 * @property string $custom
 *
 * The followings are the available model relations:
 * @property LivingPlant[] $livingPlants
 */
class AccessionNumber extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AccessionNumber the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function init() {
        parent::init();

        if ($this->isNewRecord) {
            $this->year = date("Y");
            $this->individual = "001";
        }
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_accession_number';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('year, individual', 'required'),
            array('year, individual', 'numerical', 'integerOnly' => true),
            array('custom', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, year, individual, custom', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'livingPlants' => array(self::HAS_ONE, 'LivingPlant', 'accession_number_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'year' => Yii::t('jacq', 'Year'),
            'individual' => Yii::t('jacq', 'Individual'),
            'custom' => Yii::t('jacq', 'Custom'),
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
        $criteria->compare('year', $this->year);
        $criteria->compare('individual', $this->individual);
        $criteria->compare('custom', $this->custom, true);

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
    
    public function getAccessionNumber() {
        return sprintf("%4d-%05d-%s", $this->year, $this->id, $this->custom);
    }
}
