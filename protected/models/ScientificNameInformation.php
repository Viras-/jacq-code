<?php

/**
 * This is the model class for table "tbl_scientific_name_information".
 *
 * The followings are the available columns in table 'tbl_scientific_name_information':
 * @property integer $scientific_name_id
 * @property string $spatial_distribution
 * @property string $common_names
 * @property integer $habitus_type_id
 *
 * The followings are the available model relations:
 * @property Cultivar[] $cultivars
 * @property HabitusType $habitusType
 */
class ScientificNameInformation extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_scientific_name_information';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('habitus_type_id', 'numerical', 'integerOnly' => true),
            array('spatial_distribution, common_names', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('scientific_name_id, spatial_distribution, common_names, habitus_type_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'cultivars' => array(self::HAS_MANY, 'Cultivar', 'scientific_name_id'),
            'habitusType' => array(self::BELONGS_TO, 'HabitusType', 'habitus_type_id'),
            'botanicalObjects' => array(self::HAS_MANY, 'BotanicalObject', 'scientific_name_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'scientific_name_id' => Yii::t('jacq', 'Scientific Name'),
            'spatial_distribution' => Yii::t('jacq', 'Spatial Distribution'),
            'common_names' => Yii::t('jacq', 'Common Names'),
            'habitus_type_id' => Yii::t('jacq', 'Habitus Type'),
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

        $criteria->compare('scientific_name_id', $this->scientific_name_id);
        $criteria->compare('spatial_distribution', $this->spatial_distribution, true);
        $criteria->compare('common_names', $this->common_names, true);
        $criteria->compare('habitus_type_id', $this->habitus_type_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ScientificNameInformation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
