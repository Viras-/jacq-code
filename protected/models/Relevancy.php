<?php

/**
 * This is the model class for table "tbl_relevancy".
 *
 * The followings are the available columns in table 'tbl_relevancy':
 * @property integer $id
 * @property integer $relevancy_type_id
 * @property integer $living_plant_id
 *
 * The followings are the available model relations:
 * @property RelevancyType $relevancyType
 * @property LivingPlant $livingPlant
 */
class Relevancy extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Relevancy the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_relevancy';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('relevancy_type_id, living_plant_id', 'required'),
            array('relevancy_type_id, living_plant_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, relevancy_type_id, living_plant_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'relevancyType' => array(self::BELONGS_TO, 'RelevancyType', 'relevancy_type_id'),
            'livingPlant' => array(self::BELONGS_TO, 'LivingPlant', 'living_plant_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'relevancy_type_id' => Yii::t('jacq', 'Relevancy'),
            'living_plant_id' => Yii::t('jacq', 'Living Plant'),
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
        $criteria->compare('relevancy_type_id', $this->relevancy_type_id);
        $criteria->compare('living_plant_id', $this->living_plant_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
}
