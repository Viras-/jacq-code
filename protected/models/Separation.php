<?php

/**
 * This is the model class for table "tbl_separation".
 *
 * The followings are the available columns in table 'tbl_separation':
 * @property integer $id
 * @property integer $botanical_object_id
 * @property integer $separation_type_id
 * @property string $date
 * @property string $annotation
 *
 * The followings are the available model relations:
 * @property SeparationType $separationType
 * @property BotanicalObject $botanicalObject
 */
class Separation extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Separation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_separation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('botanical_object_id, separation_type_id', 'required'),
            array('botanical_object_id, separation_type_id', 'numerical', 'integerOnly' => true),
            array('date, annotation', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, botanical_object_id, separation_type_id, date, annotation', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'separationType' => array(self::BELONGS_TO, 'SeparationType', 'separation_type_id'),
            'botanicalObject' => array(self::BELONGS_TO, 'BotanicalObject', 'botanical_object_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'botanical_object_id' => Yii::t('jacq', 'Botanical Object'),
            'separation_type_id' => Yii::t('jacq', 'Separation Type'),
            'date' => Yii::t('jacq', 'Date'),
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
        $criteria->compare('botanical_object_id', $this->botanical_object_id);
        $criteria->compare('separation_type_id', $this->separation_type_id);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('annotation', $this->annotation, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
}
