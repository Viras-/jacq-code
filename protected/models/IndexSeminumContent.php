<?php

/**
 * This is the model class for table "tbl_index_seminum_content".
 *
 * The followings are the available columns in table 'tbl_index_seminum_content':
 * @property integer $index_seminum_content_id
 * @property integer $index_seminum_revision_id
 * @property integer $botanical_object_id
 * @property integer $accession_number
 * @property string $family
 * @property string $scientific_name
 * @property string $index_seminum_type
 * @property string $ipen_number
 * @property string $acquisition_number
 * @property string $acquisition_location
 * @property string $habitat
 * @property integer $altitude_min
 * @property integer $altitude_max
 * @property string $latitude
 * @property string $longitude
 * @property string $acquisition_date
 * @property string $timestamp
 *
 * The followings are the available model relations:
 * @property BotanicalObject $botanicalObject
 * @property IndexSeminumRevision $indexSeminumRevision
 * @property IndexSeminumPerson[] $indexSeminumPeople
 */
class IndexSeminumContent extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_index_seminum_content';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('index_seminum_revision_id, botanical_object_id, accession_number, family, scientific_name, index_seminum_type, ipen_number', 'required'),
            array('index_seminum_revision_id, botanical_object_id, accession_number, altitude_min, altitude_max', 'numerical', 'integerOnly' => true),
            array('index_seminum_type', 'length', 'max' => 3),
            array('ipen_number', 'length', 'max' => 20),
            array('latitude, longitude', 'length', 'max' => 14),
            array('habitat', 'length', 'max' => 45),
            array('acquisition_date', 'length', 'max' => 20),
            array('acquisition_number, acquisition_location', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('index_seminum_content_id, index_seminum_revision_id, botanical_object_id, accession_number, family, scientific_name, index_seminum_type, ipen_number, acquisition_number, acquisition_location, habitat, altitude_min, altitude_max, latitude, longitude, acquisition_date, timestamp', 'safe', 'on' => 'search'),
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
            'indexSeminumRevision' => array(self::BELONGS_TO, 'IndexSeminumRevision', 'index_seminum_revision_id'),
            'indexSeminumPeople' => array(self::HAS_MANY, 'IndexSeminumPerson', 'index_seminum_content_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'index_seminum_content_id' => Yii::t('jacq', 'Index Seminum Content'),
            'index_seminum_revision_id' => Yii::t('jacq', 'Index Seminum Revision'),
            'botanical_object_id' => Yii::t('jacq', 'Botanical Object'),
            'accession_number' => Yii::t('jacq', 'Accession Number'),
            'family' => Yii::t('jacq', 'Family'),
            'scientific_name' => Yii::t('jacq', 'Scientific Name'),
            'index_seminum_type' => Yii::t('jacq', 'Index Seminum Type'),
            'ipen_number' => Yii::t('jacq', 'IPEN Number'),
            'acquisition_number' => Yii::t('jacq', 'Acquisition Number'),
            'acquisition_location' => Yii::t('jacq', 'Acquisition Locality'),
            'habitat' => Yii::t('jacq', 'Habitat'),
            'altitude_min' => Yii::t('jacq', 'Altitude Min'),
            'altitude_max' => Yii::t('jacq', 'Altitude Max'),
            'latitude' => Yii::t('jacq', 'Latitude'),
            'longitude' => Yii::t('jacq', 'Longitude'),
            'acquisition_date' => Yii::t('jacq', 'Acquisition Date'),
            'timestamp' => Yii::t('jacq', 'Timestamp')
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

        $criteria->compare('index_seminum_content_id', $this->index_seminum_content_id);
        $criteria->compare('index_seminum_revision_id', $this->index_seminum_revision_id);
        $criteria->compare('botanical_object_id', $this->botanical_object_id);
        $criteria->compare('accession_number', $this->accession_number);
        $criteria->compare('family', $this->family, true);
        $criteria->compare('scientific_name', $this->scientific_name, true);
        $criteria->compare('index_seminum_type', $this->index_seminum_type, true);
        $criteria->compare('ipen_number', $this->ipen_number, true);
        $criteria->compare('acquisition_number', $this->acquisition_number, true);
        $criteria->compare('acquisition_location', $this->acquisition_location, true);
        $criteria->compare('habitat', $this->habitat, true);
        $criteria->compare('altitude_min', $this->altitude_min);
        $criteria->compare('altitude_max', $this->altitude_max);
        $criteria->compare('latitude', $this->latitude, true);
        $criteria->compare('longitude', $this->longitude, true);
        $criteria->compare('acquisition_date', $this->acquisition_date, true);
        $criteria->compare('timestamp', $this->timestamp, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return IndexSeminumContent the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
