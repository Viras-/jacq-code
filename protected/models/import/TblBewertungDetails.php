<?php

/**
 * This is the model class for table "tblBewertung_Details".
 *
 * The followings are the available columns in table 'tblBewertung_Details':
 * @property integer $IDPflanze
 * @property integer $Bewertungsjahr
 * @property integer $Grobast
 * @property integer $Belaubung
 * @property integer $Totholz
 * @property integer $Gabelzustand
 * @property integer $Stammfuß
 * @property integer $Stamm
 * @property integer $Stammkopf
 * @property integer $Starkäste
 * @property integer $Fäulnis
 * @property integer $Wassertaschen
 * @property integer $Allgemeinzustand
 * @property integer $Schädigungsgrad
 * @property integer $Lebenswerwartung
 * @property integer $Gesamtbewertung
 * @property string $Kronensicherung
 */
class TblBewertungDetails extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TblBewertungDetails the static model class
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
		return 'tblBewertung_Details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDPflanze', 'required'),
			array('IDPflanze, Bewertungsjahr, Grobast, Belaubung, Totholz, Gabelzustand, Stammfuß, Stamm, Stammkopf, Starkäste, Fäulnis, Wassertaschen, Allgemeinzustand, Schädigungsgrad, Lebenswerwartung, Gesamtbewertung', 'numerical', 'integerOnly'=>true),
			array('Kronensicherung', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDPflanze, Bewertungsjahr, Grobast, Belaubung, Totholz, Gabelzustand, Stammfuß, Stamm, Stammkopf, Starkäste, Fäulnis, Wassertaschen, Allgemeinzustand, Schädigungsgrad, Lebenswerwartung, Gesamtbewertung, Kronensicherung', 'safe', 'on'=>'search'),
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
			'Bewertungsjahr' => 'Bewertungsjahr',
			'Grobast' => 'Grobast',
			'Belaubung' => 'Belaubung',
			'Totholz' => 'Totholz',
			'Gabelzustand' => 'Gabelzustand',
			'Stammfuß' => 'Stammfuß',
			'Stamm' => 'Stamm',
			'Stammkopf' => 'Stammkopf',
			'Starkäste' => 'Starkäste',
			'Fäulnis' => 'Fäulnis',
			'Wassertaschen' => 'Wassertaschen',
			'Allgemeinzustand' => 'Allgemeinzustand',
			'Schädigungsgrad' => 'Schädigungsgrad',
			'Lebenswerwartung' => 'Lebenswerwartung',
			'Gesamtbewertung' => 'Gesamtbewertung',
			'Kronensicherung' => 'Kronensicherung',
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
		$criteria->compare('Bewertungsjahr',$this->Bewertungsjahr);
		$criteria->compare('Grobast',$this->Grobast);
		$criteria->compare('Belaubung',$this->Belaubung);
		$criteria->compare('Totholz',$this->Totholz);
		$criteria->compare('Gabelzustand',$this->Gabelzustand);
		$criteria->compare('Stammfuß',$this->Stammfuß);
		$criteria->compare('Stamm',$this->Stamm);
		$criteria->compare('Stammkopf',$this->Stammkopf);
		$criteria->compare('Starkäste',$this->Starkäste);
		$criteria->compare('Fäulnis',$this->Fäulnis);
		$criteria->compare('Wassertaschen',$this->Wassertaschen);
		$criteria->compare('Allgemeinzustand',$this->Allgemeinzustand);
		$criteria->compare('Schädigungsgrad',$this->Schädigungsgrad);
		$criteria->compare('Lebenswerwartung',$this->Lebenswerwartung);
		$criteria->compare('Gesamtbewertung',$this->Gesamtbewertung);
		$criteria->compare('Kronensicherung',$this->Kronensicherung,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}