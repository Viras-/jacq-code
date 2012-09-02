<?php

/**
 * This is the model class for table "tbl_phenology".
 *
 * The followings are the available columns in table 'tbl_phenology':
 * @property integer $id
 * @property string $phenology
 *
 * The followings are the available model relations:
 * @property BotanicalObject[] $botanicalObjects
 */
class Phenology extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Phenology the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_phenology';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('phenology', 'length', 'max' => 45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, phenology', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'botanicalObjects' => array(self::HAS_MANY, 'BotanicalObject', 'phenology_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'phenology' => Yii::t('jacq', 'Phenology'),
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
        $criteria->compare('phenology', $this->phenology, true);

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
}
