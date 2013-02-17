<?php

/**
 * This is the model class for table "Herkunft".
 *
 * The followings are the available columns in table 'Herkunft':
 * @property integer $IDPflanze
 * @property string $Collector
 * @property string $CollNr
 * @property string $CollLand
 * @property string $CollOrt
 * @property string $Standort
 * @property string $CollDatum
 * @property string $Bezugsquelle
 * @property string $DetName
 * @property string $DetDatum
 */
class Herkunft extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Herkunft the static model class
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
		return 'Herkunft';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDPflanze', 'numerical', 'integerOnly'=>true),
			array('Collector, CollNr, CollLand, CollOrt, Bezugsquelle, DetName, DetDatum', 'length', 'max'=>255),
			array('Standort, CollDatum', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDPflanze, Collector, CollNr, CollLand, CollOrt, Standort, CollDatum, Bezugsquelle, DetName, DetDatum', 'safe', 'on'=>'search'),
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
			'IDPflanze' => 'Idpflanze',
			'Collector' => 'Collector',
			'CollNr' => 'Coll Nr',
			'CollLand' => 'Coll Land',
			'CollOrt' => 'Coll Ort',
			'Standort' => 'Standort',
			'CollDatum' => 'Coll Datum',
			'Bezugsquelle' => 'Bezugsquelle',
			'DetName' => 'Det Name',
			'DetDatum' => 'Det Datum',
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

		$criteria->compare('IDPflanze',$this->IDPflanze);
		$criteria->compare('Collector',$this->Collector,true);
		$criteria->compare('CollNr',$this->CollNr,true);
		$criteria->compare('CollLand',$this->CollLand,true);
		$criteria->compare('CollOrt',$this->CollOrt,true);
		$criteria->compare('Standort',$this->Standort,true);
		$criteria->compare('CollDatum',$this->CollDatum,true);
		$criteria->compare('Bezugsquelle',$this->Bezugsquelle,true);
		$criteria->compare('DetName',$this->DetName,true);
		$criteria->compare('DetDatum',$this->DetDatum,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}