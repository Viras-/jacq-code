<?php

/**
 * This is the model class for table "tbl_habitus_type".
 *
 * The followings are the available columns in table 'tbl_habitus_type':
 * @property integer $habitus_type_id
 * @property string $habitus
 *
 * The followings are the available model relations:
 * @property ScientificNameInformation[] $scientificNameInformations
 */
class HabitusType extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_habitus_type';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('habitus_type_id, habitus', 'required'),
            array('habitus_type_id', 'numerical', 'integerOnly' => true),
            array('habitus', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('habitus_type_id, habitus', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'scientificNameInformations' => array(self::HAS_MANY, 'ScientificNameInformation', 'habitus_type_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'habitus_type_id' => Yii::t('jacq', 'Habitus Type'),
            'habitus' => Yii::t('jacq', 'Habitus'),
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

        $criteria->compare('habitus_type_id', $this->habitus_type_id);
        $criteria->compare('habitus', $this->habitus, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return HabitusType the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
