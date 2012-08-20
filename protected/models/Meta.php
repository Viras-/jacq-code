<?php

/**
 * This is the model class for table "meta".
 *
 * The followings are the available columns in table 'meta':
 * @property integer $source_id
 * @property string $source_code
 * @property string $source_name
 * @property string $source_update
 * @property string $source_version
 * @property string $source_url
 * @property string $source_expiry
 * @property integer $source_number_of_records
 * @property string $source_abbr_engl
 */
class Meta extends CActiveRecord {

    public function getDbConnection() {
        return Yii::app()->dbHerbarInput;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Meta the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'meta';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('source_id, source_number_of_records', 'numerical', 'integerOnly' => true),
            array('source_code, source_name, source_version, source_url', 'length', 'max' => 250),
            array('source_abbr_engl', 'length', 'max' => 255),
            array('source_update, source_expiry', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('source_id, source_code, source_name, source_update, source_version, source_url, source_expiry, source_number_of_records, source_abbr_engl', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'source_id' => 'Source',
            'source_code' => 'Source Code',
            'source_name' => 'Source Name',
            'source_update' => 'Source Update',
            'source_version' => 'Source Version',
            'source_url' => 'Source Url',
            'source_expiry' => 'Source Expiry',
            'source_number_of_records' => 'Source Number Of Records',
            'source_abbr_engl' => 'Source Abbr Engl',
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

        $criteria->compare('source_id', $this->source_id);
        $criteria->compare('source_code', $this->source_code, true);
        $criteria->compare('source_name', $this->source_name, true);
        $criteria->compare('source_update', $this->source_update, true);
        $criteria->compare('source_version', $this->source_version, true);
        $criteria->compare('source_url', $this->source_url, true);
        $criteria->compare('source_expiry', $this->source_expiry, true);
        $criteria->compare('source_number_of_records', $this->source_number_of_records);
        $criteria->compare('source_abbr_engl', $this->source_abbr_engl, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
