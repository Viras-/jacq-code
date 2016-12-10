<?php

/**
 * This is the model class for table "tbl_specimen".
 *
 * The followings are the available columns in table 'tbl_specimen':
 * @property integer $specimen_id
 * @property integer $botanical_object_id
 * @property integer $curatorial_unit_id
 * @property string $barcode
 * @property string $timestamp
 *
 * The followings are the available model relations:
 * @property BotanicalObject $botanicalObject
 * @property CuratorialUnit $curatorialUnit
 */
class Specimen extends CActiveRecord {

    /**
     * @var helper attribute for deleting a specimen entry
     */
    public $delete = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_specimen';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('botanical_object_id, curatorial_unit_id, timestamp', 'required'),
            array('botanical_object_id, curatorial_unit_id', 'numerical', 'integerOnly' => true),
            array('barcode', 'length', 'max' => 20),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('specimen_id, botanical_object_id, curatorial_unit_id, barcode, timestamp', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'botanicalObject' => array(self::BELONGS_TO, 'BotanicalObject', 'botanical_object_id'),
            'curatorialUnit' => array(self::BELONGS_TO, 'CuratorialUnit', 'curatorial_unit_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'specimen_id' => 'Specimen',
            'botanical_object_id' => 'Botanical Object',
            'curatorial_unit_id' => 'Curatorial Unit',
            'barcode' => 'Barcode',
            'timestamp' => 'Timestamp',
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

        $criteria->compare('specimen_id', $this->specimen_id);
        $criteria->compare('botanical_object_id', $this->botanical_object_id);
        $criteria->compare('curatorial_unit_id', $this->curatorial_unit_id);
        $criteria->compare('barcode', $this->barcode, true);
        $criteria->compare('timestamp', $this->timestamp, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Specimen the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
