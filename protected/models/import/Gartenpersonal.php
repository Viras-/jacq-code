<?php

/**
 * This is the model class for table "Gartenpersonal".
 *
 * The followings are the available columns in table 'Gartenpersonal':
 * @property integer $IDGärtner
 * @property string $Titel
 * @property string $Nachname
 * @property string $Vorname
 * @property string $Foto
 * @property string $Geburtsdatum
 * @property integer $Lehrling
 * @property integer $Stammpersonal
 * @property integer $Ausgetreten
 */
class Gartenpersonal extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Gartenpersonal the static model class
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
		return 'Gartenpersonal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Lehrling, Stammpersonal, Ausgetreten', 'numerical', 'integerOnly'=>true),
			array('Titel, Nachname, Vorname', 'length', 'max'=>255),
			array('Foto, Geburtsdatum', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDGärtner, Titel, Nachname, Vorname, Foto, Geburtsdatum, Lehrling, Stammpersonal, Ausgetreten', 'safe', 'on'=>'search'),
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
			'IDGärtner' => 'Idgärtner',
			'Titel' => 'Titel',
			'Nachname' => 'Nachname',
			'Vorname' => 'Vorname',
			'Foto' => 'Foto',
			'Geburtsdatum' => 'Geburtsdatum',
			'Lehrling' => 'Lehrling',
			'Stammpersonal' => 'Stammpersonal',
			'Ausgetreten' => 'Ausgetreten',
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

		$criteria->compare('IDGärtner',$this->IDGärtner);
		$criteria->compare('Titel',$this->Titel,true);
		$criteria->compare('Nachname',$this->Nachname,true);
		$criteria->compare('Vorname',$this->Vorname,true);
		$criteria->compare('Foto',$this->Foto,true);
		$criteria->compare('Geburtsdatum',$this->Geburtsdatum,true);
		$criteria->compare('Lehrling',$this->Lehrling);
		$criteria->compare('Stammpersonal',$this->Stammpersonal);
		$criteria->compare('Ausgetreten',$this->Ausgetreten);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}