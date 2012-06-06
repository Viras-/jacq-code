<?php

/**
 * This is the model class for table "jacq_input.tbl_acquisition_date".
 *
 * The followings are the available columns in table 'jacq_input.tbl_acquisition_date':
 * @property integer $id
 * @property string $year
 * @property string $month
 * @property string $day
 * @property string $custom
 *
 * The followings are the available model relations:
 * @property AcquisitionEvent[] $acquisitionEvents
 */
class AcquisitionDate extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AcquisitionDate the static model class
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
		return 'jacq_input.tbl_acquisition_date';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('year', 'length', 'max'=>4),
			array('month, day', 'length', 'max'=>2),
			array('custom', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, year, month, day, custom', 'safe', 'on'=>'search'),
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
			'acquisitionEvents' => array(self::HAS_MANY, 'AcquisitionEvent', 'acquisition_date_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'year' => 'Year',
			'month' => 'Month',
			'day' => 'Day',
			'custom' => 'Custom',
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
		$criteria->compare('year',$this->year,true);
		$criteria->compare('month',$this->month,true);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('custom',$this->custom,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}