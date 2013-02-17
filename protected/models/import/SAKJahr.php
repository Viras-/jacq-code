<?php

/**
 * This is the model class for table "SAKJahr".
 *
 * The followings are the available columns in table 'SAKJahr':
 * @property integer $IDSAK
 * @property string $SAKJahr
 * @property string $Herkunft
 * @property string $Collector
 * @property string $Sammeldatum
 */
class SAKJahr extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SAKJahr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return CDbConnection database connection
	 */
	public function getDbConnection()
	{
		return Yii::app()->dbImport;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'SAKJahr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('SAKJahr', 'required'),
			array('IDSAK', 'numerical', 'integerOnly'=>true),
			array('Herkunft, Collector', 'length', 'max'=>255),
			array('Sammeldatum', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDSAK, SAKJahr, Herkunft, Collector, Sammeldatum', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'IDSAK' => 'Idsak',
			'SAKJahr' => 'Sakjahr',
			'Herkunft' => 'Herkunft',
			'Collector' => 'Collector',
			'Sammeldatum' => 'Sammeldatum',
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

		$criteria->compare('IDSAK',$this->IDSAK);
		$criteria->compare('SAKJahr',$this->SAKJahr,true);
		$criteria->compare('Herkunft',$this->Herkunft,true);
		$criteria->compare('Collector',$this->Collector,true);
		$criteria->compare('Sammeldatum',$this->Sammeldatum,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}