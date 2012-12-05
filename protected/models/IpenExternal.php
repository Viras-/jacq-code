<?php

/**
 * This is the model class for table "tbl_ipen_external".
 *
 * The followings are the available columns in table 'tbl_ipen_external':
 * @property integer $id
 * @property integer $living_plant_id
 * @property string $ipen_number
 *
 * The followings are the available model relations:
 * @property LivingPlant $livingPlant
 */
class IpenExternal extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IpenExternal the static model class
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
		return 'tbl_ipen_external';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('living_plant_id, ipen_number', 'required'),
			array('living_plant_id', 'numerical', 'integerOnly'=>true),
			array('ipen_number', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, living_plant_id, ipen_number', 'safe', 'on'=>'search'),
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
			'livingPlant' => array(self::BELONGS_TO, 'LivingPlant', 'living_plant_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'living_plant_id' => 'Living Plant',
			'ipen_number' => 'Ipen Number',
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
		$criteria->compare('living_plant_id',$this->living_plant_id);
		$criteria->compare('ipen_number',$this->ipen_number,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}