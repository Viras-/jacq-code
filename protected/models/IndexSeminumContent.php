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
 * @property string $acquisition_locality
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
class IndexSeminumContent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_index_seminum_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('index_seminum_revision_id, botanical_object_id, accession_number, family, scientific_name, index_seminum_type, ipen_number', 'required'),
			array('index_seminum_revision_id, botanical_object_id, accession_number, altitude_min, altitude_max', 'numerical', 'integerOnly'=>true),
			array('index_seminum_type', 'length', 'max'=>3),
			array('ipen_number', 'length', 'max'=>20),
			array('acquisition_locality, habitat, latitude, longitude, acquisition_date', 'length', 'max'=>45),
			array('acquisition_number', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('index_seminum_content_id, index_seminum_revision_id, botanical_object_id, accession_number, family, scientific_name, index_seminum_type, ipen_number, acquisition_number, acquisition_locality, habitat, altitude_min, altitude_max, latitude, longitude, acquisition_date, timestamp', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
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
	public function attributeLabels()
	{
		return array(
			'index_seminum_content_id' => 'Index Seminum Content',
			'index_seminum_revision_id' => 'Index Seminum Revision',
			'botanical_object_id' => 'Botanical Object',
			'accession_number' => 'Accession Number',
			'family' => 'Family',
			'scientific_name' => 'Scientific Name',
			'index_seminum_type' => 'Index Seminum Type',
			'ipen_number' => 'Ipen Number',
			'acquisition_number' => 'Acquisition Number',
			'acquisition_locality' => 'Acquisition Locality',
			'habitat' => 'Habitat',
			'altitude_min' => 'Altitude Min',
			'altitude_max' => 'Altitude Max',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'acquisition_date' => 'Acquisition Date',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('index_seminum_content_id',$this->index_seminum_content_id);
		$criteria->compare('index_seminum_revision_id',$this->index_seminum_revision_id);
		$criteria->compare('botanical_object_id',$this->botanical_object_id);
		$criteria->compare('accession_number',$this->accession_number);
		$criteria->compare('family',$this->family,true);
		$criteria->compare('scientific_name',$this->scientific_name,true);
		$criteria->compare('index_seminum_type',$this->index_seminum_type,true);
		$criteria->compare('ipen_number',$this->ipen_number,true);
		$criteria->compare('acquisition_number',$this->acquisition_number,true);
		$criteria->compare('acquisition_locality',$this->acquisition_locality,true);
		$criteria->compare('habitat',$this->habitat,true);
		$criteria->compare('altitude_min',$this->altitude_min);
		$criteria->compare('altitude_max',$this->altitude_max);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('acquisition_date',$this->acquisition_date,true);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IndexSeminumContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
