<?php

/**
 * This is the model class for table "Artenschutz".
 *
 * The followings are the available columns in table 'Artenschutz':
 * @property integer $IDArt
 * @property integer $Artenschutz
 * @property integer $RoteListe
 * @property integer $Burgenland
 * @property integer $Kärnten
 * @property integer $Niederösterreich
 * @property integer $Oberösterreich
 * @property integer $Salzburg
 * @property integer $Steiermark
 * @property integer $Südtirol
 * @property integer $Tirol
 * @property integer $Vorarlberg
 * @property integer $Wien
 * @property integer $Cites
 * @property integer $BernConv
 * @property string $IUCN
 * @property string $Int
 * @property string $EU
 * @property string $Ersteller
 * @property string $Erstelldatum
 */
class Artenschutz extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Artenschutz the static model class
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
		return 'Artenschutz';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDArt, Artenschutz, RoteListe, Burgenland, Kärnten, Niederösterreich, Oberösterreich, Salzburg, Steiermark, Südtirol, Tirol, Vorarlberg, Wien, Cites, BernConv', 'numerical', 'integerOnly'=>true),
			array('IUCN', 'length', 'max'=>2),
			array('Int, EU', 'length', 'max'=>1),
			array('Ersteller, Erstelldatum', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDArt, Artenschutz, RoteListe, Burgenland, Kärnten, Niederösterreich, Oberösterreich, Salzburg, Steiermark, Südtirol, Tirol, Vorarlberg, Wien, Cites, BernConv, IUCN, Int, EU, Ersteller, Erstelldatum', 'safe', 'on'=>'search'),
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
			'IDArt' => 'Idart',
			'Artenschutz' => 'Artenschutz',
			'RoteListe' => 'Rote Liste',
			'Burgenland' => 'Burgenland',
			'Kärnten' => 'Kärnten',
			'Niederösterreich' => 'Niederösterreich',
			'Oberösterreich' => 'Oberösterreich',
			'Salzburg' => 'Salzburg',
			'Steiermark' => 'Steiermark',
			'Südtirol' => 'Südtirol',
			'Tirol' => 'Tirol',
			'Vorarlberg' => 'Vorarlberg',
			'Wien' => 'Wien',
			'Cites' => 'Cites',
			'BernConv' => 'Bern Conv',
			'IUCN' => 'Iucn',
			'Int' => 'Int',
			'EU' => 'Eu',
			'Ersteller' => 'Ersteller',
			'Erstelldatum' => 'Erstelldatum',
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

		$criteria->compare('IDArt',$this->IDArt);
		$criteria->compare('Artenschutz',$this->Artenschutz);
		$criteria->compare('RoteListe',$this->RoteListe);
		$criteria->compare('Burgenland',$this->Burgenland);
		$criteria->compare('Kärnten',$this->Kärnten);
		$criteria->compare('Niederösterreich',$this->Niederösterreich);
		$criteria->compare('Oberösterreich',$this->Oberösterreich);
		$criteria->compare('Salzburg',$this->Salzburg);
		$criteria->compare('Steiermark',$this->Steiermark);
		$criteria->compare('Südtirol',$this->Südtirol);
		$criteria->compare('Tirol',$this->Tirol);
		$criteria->compare('Vorarlberg',$this->Vorarlberg);
		$criteria->compare('Wien',$this->Wien);
		$criteria->compare('Cites',$this->Cites);
		$criteria->compare('BernConv',$this->BernConv);
		$criteria->compare('IUCN',$this->IUCN,true);
		$criteria->compare('Int',$this->Int,true);
		$criteria->compare('EU',$this->EU,true);
		$criteria->compare('Ersteller',$this->Ersteller,true);
		$criteria->compare('Erstelldatum',$this->Erstelldatum,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}