<?php

/**
 * This is the model class for table "tbl_botanical_object_sex".
 *
 * The followings are the available columns in table 'tbl_botanical_object_sex':
 * @property integer $id
 * @property integer $sex_id
 * @property integer $botanical_object_id
 *
 * The followings are the available model relations:
 * @property Sex $sex
 * @property BotanicalObject $botanicalObject
 */
class BotanicalObjectSex extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BotanicalObjectSex the static model class
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
		return 'tbl_botanical_object_sex';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sex_id, botanical_object_id', 'required'),
			array('sex_id, botanical_object_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sex_id, botanical_object_id', 'safe', 'on'=>'search'),
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
			'botanicalObject' => array(self::BELONGS_TO, 'BotanicalObject', 'botanical_object_id'),
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
			'botanical_object_id' => 'Botanical Object',
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
		$criteria->compare('botanical_object_id',$this->botanical_object_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}