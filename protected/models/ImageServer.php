<?php

/**
 * This is the model class for table "tbl_image_server".
 *
 * The followings are the available columns in table 'tbl_image_server':
 * @property integer $organisation_id
 * @property string $base_url
 * @property string $key
 *
 * The followings are the available model relations:
 * @property Organisation $organisation
 */
class ImageServer extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_image_server';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('organisation_id, base_url, key', 'required'),
            array('organisation_id', 'numerical', 'integerOnly' => true),
            array('key', 'length', 'max' => 50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('organisation_id, base_url, key', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'organisation' => array(self::BELONGS_TO, 'Organisation', 'organisation_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'organisation_id' => 'Organisation',
            'base_url' => 'Base Url',
            'key' => 'Key',
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

        $criteria->compare('organisation_id', $this->organisation_id);
        $criteria->compare('base_url', $this->base_url, true);
        $criteria->compare('key', $this->key, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ImageServer the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
