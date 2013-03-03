<?php

/**
 * This is the model class for table "tbl_import_properties".
 *
 * The followings are the available columns in table 'tbl_import_properties':
 * @property integer $id
 * @property integer $botanical_object_id
 * @property integer $IDPflanze
 * @property string $species_name
 *
 * The followings are the available model relations:
 * @property BotanicalObject $botanicalObject
 */
class ImportProperties extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ImportProperties the static model class
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
		return 'tbl_import_properties';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('botanical_object_id', 'required'),
			array('botanical_object_id, IDPflanze', 'numerical', 'integerOnly'=>true),
			array('species_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, botanical_object_id, IDPflanze, species_name', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'botanical_object_id' => 'Botanical Object',
			'IDPflanze' => 'Idpflanze',
			'species_name' => 'Species Name',
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
		$criteria->compare('botanical_object_id',$this->botanical_object_id);
		$criteria->compare('IDPflanze',$this->IDPflanze);
		$criteria->compare('species_name',$this->species_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}