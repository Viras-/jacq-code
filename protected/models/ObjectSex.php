<?php

/**
 * This is the model class for table "jacq_input.tbl_object_sex".
 *
 * The followings are the available columns in table 'jacq_input.tbl_object_sex':
 * @property integer $id
 * @property integer $sex_id
 * @property integer $object_id
 *
 * The followings are the available model relations:
 * @property Sex $sex
 * @property BotanicalObject $object
 */
class ObjectSex extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ObjectSex the static model class
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
		return 'jacq_input.tbl_object_sex';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, sex_id, object_id', 'required'),
			array('id, sex_id, object_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sex_id, object_id', 'safe', 'on'=>'search'),
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
			'sex' => array(self::BELONGS_TO, 'Sex', 'sex_id'),
			'object' => array(self::BELONGS_TO, 'BotanicalObject', 'object_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sex_id' => 'Sex',
			'object_id' => 'Object',
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
		$criteria->compare('sex_id',$this->sex_id);
		$criteria->compare('object_id',$this->object_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}