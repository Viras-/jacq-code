<?php

/**
 * This is the model class for table "tbl_certificate".
 *
 * The followings are the available columns in table 'tbl_certificate':
 * @property integer $id
 * @property integer $living_plant_id
 * @property integer $certificate_type_id
 * @property string $number
 * @property string $annotation
 *
 * The followings are the available model relations:
 * @property CertificateType $certificateType
 * @property LivingPlant $livingPlant
 */
class Certificate extends ActiveRecord {
    /**
     * @var int helper attribute for deleting certificate entries
     */
    public $delete = 0;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Certificate the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_certificate';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('living_plant_id, certificate_type_id', 'required'),
            array('living_plant_id, certificate_type_id', 'numerical', 'integerOnly' => true),
            array('number, annotation', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, living_plant_id, certificate_type_id, annotation', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'certificateType' => array(self::BELONGS_TO, 'CertificateType', 'certificate_type_id'),
            'livingPlant' => array(self::BELONGS_TO, 'LivingPlant', 'living_plant_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'living_plant_id' => Yii::t('jacq', 'Living Plant'),
            'certificate_type_id' => Yii::t('jacq', 'Certificate Type'),
            'number' => Yii::t('jacq', 'Number'),
            'annotation' => Yii::t('jacq', 'Annotation'),
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
        $criteria->compare('living_plant_id', $this->living_plant_id);
        $criteria->compare('certificate_type_id', $this->certificate_type_id);
        $criteria->compare('annotation', $this->annotation, true);
        $criteria->compare('number', $this->annotation, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
}
