<?php

/**
 * This is the model class for table "jacq_input.tbl_acquisition_event".
 *
 * The followings are the available columns in table 'jacq_input.tbl_acquisition_event':
 * @property integer $id
 * @property string $agent_id
 * @property integer $acquisition_date_id
 * @property integer $acquisition_type_id
 * @property integer $location_id
 * @property string $number
 *
 * The followings are the available model relations:
 * @property AcquisitionDate $acquisitionDate
 * @property AcquisitionType $acquisitionType
 * @property BotanicalObject[] $botanicalObjects
 */
class AcquisitionEvent extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AcquisitionEvent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'jacq_input.tbl_acquisition_event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, acquisition_date_id, acquisition_type_id', 'required'),
			array('id, acquisition_date_id, acquisition_type_id, location_id', 'numerical', 'integerOnly'=>true),
			array('agent_id, number', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, agent_id, acquisition_date_id, acquisition_type_id, location_id, number', 'safe', 'on'=>'search'),
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
			'acquisitionDate' => array(self::BELONGS_TO, 'AcquisitionDate', 'acquisition_date_id'),
			'acquisitionType' => array(self::BELONGS_TO, 'AcquisitionType', 'acquisition_type_id'),
			'botanicalObjects' => array(self::HAS_MANY, 'BotanicalObject', 'acquisition_event_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'agent_id' => 'Agent',
			'acquisition_date_id' => 'Acquisition Date',
			'acquisition_type_id' => 'Acquisition Type',
			'location_id' => 'Location',
			'number' => 'Number',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('agent_id',$this->agent_id,true);
		$criteria->compare('acquisition_date_id',$this->acquisition_date_id);
		$criteria->compare('acquisition_type_id',$this->acquisition_type_id);
		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('number',$this->number,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}