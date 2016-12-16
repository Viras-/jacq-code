<?php

/**
 * This is the model class for table "tbl_separation".
 *
 * The followings are the available columns in table 'tbl_separation':
 * @property integer $id
 * @property integer $botanical_object_id
 * @property integer $derivative_vegetative_id
 * @property integer $separation_type_id
 * @property string $date
 * @property string $annotation
 *
 * The followings are the available model relations:
 * @property SeparationType $separationType
 * @property BotanicalObject $botanicalObject
 * @property DerivativeVegetative $derivativeVegetative
 */
class Separation extends ActiveRecord {

    /**
     * @var helper attribute for deleting a separation entry
     */
    public $delete = 0;

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
            array('separation_type_id', 'required'),
            array('botanical_object_id, derivative_vegetative_id, separation_type_id', 'numerical', 'integerOnly' => true),
            array('date, annotation', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, botanical_object_id, derivative_vegetative_id, separation_type_id, date, annotation', 'safe', 'on' => 'search'),
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
            'derivativeVegetative' => array(self::BELONGS_TO, 'DerivativeVegetative', 'derivative_vegetative_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'botanical_object_id' => 'Botanical Object',
            'derivative_vegetative_id' => 'Derivative Vegetative',
            'separation_type_id' => 'Separation Type',
            'date' => 'Date',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('botanical_object_id', $this->botanical_object_id);
        $criteria->compare('derivative_vegetative_id', $this->derivative_vegetative_id);
        $criteria->compare('separation_type_id', $this->separation_type_id);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('annotation', $this->annotation, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Separation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
