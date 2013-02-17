<?php

/**
 * This is the model class for table "Daten".
 *
 * The followings are the available columns in table 'Daten':
 * @property integer $IDAdresse
 * @property string $Vorname
 * @property string $Nachname
 * @property string $gfirma
 * @property string $gabteilung
 * @property string $Land
 * @property string $Straße
 * @property string $Nr
 * @property string $Plz
 * @property string $Ort
 * @property string $eMail1
 * @property string $website
 * @property string $kommentar
 * @property string $Anrede
 * @property string $k1
 * @property string $k2
 * @property string $k3
 * @property string $k4
 * @property string $k5
 * @property string $k1typ
 * @property string $k2typ
 * @property string $k3typ
 * @property string $k4typ
 * @property string $k5typ
 */
class Daten extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Daten the static model class
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
		return 'Daten';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Vorname, Nachname, Straße, Nr', 'required'),
			array('Vorname, Nachname, Straße, Nr, Plz, Ort, eMail1, Anrede, k1, k2, k3, k4, k5, k1typ, k2typ, k3typ, k4typ, k5typ', 'length', 'max'=>50),
			array('gfirma, gabteilung, Land', 'length', 'max'=>255),
			array('website, kommentar', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDAdresse, Vorname, Nachname, gfirma, gabteilung, Land, Straße, Nr, Plz, Ort, eMail1, website, kommentar, Anrede, k1, k2, k3, k4, k5, k1typ, k2typ, k3typ, k4typ, k5typ', 'safe', 'on'=>'search'),
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
			'IDAdresse' => 'Idadresse',
			'Vorname' => 'Vorname',
			'Nachname' => 'Nachname',
			'gfirma' => 'Gfirma',
			'gabteilung' => 'Gabteilung',
			'Land' => 'Land',
			'Straße' => 'Straße',
			'Nr' => 'Nr',
			'Plz' => 'Plz',
			'Ort' => 'Ort',
			'eMail1' => 'E Mail1',
			'website' => 'Website',
			'kommentar' => 'Kommentar',
			'Anrede' => 'Anrede',
			'k1' => 'K1',
			'k2' => 'K2',
			'k3' => 'K3',
			'k4' => 'K4',
			'k5' => 'K5',
			'k1typ' => 'K1typ',
			'k2typ' => 'K2typ',
			'k3typ' => 'K3typ',
			'k4typ' => 'K4typ',
			'k5typ' => 'K5typ',
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

		$criteria->compare('IDAdresse',$this->IDAdresse);
		$criteria->compare('Vorname',$this->Vorname,true);
		$criteria->compare('Nachname',$this->Nachname,true);
		$criteria->compare('gfirma',$this->gfirma,true);
		$criteria->compare('gabteilung',$this->gabteilung,true);
		$criteria->compare('Land',$this->Land,true);
		$criteria->compare('Straße',$this->Straße,true);
		$criteria->compare('Nr',$this->Nr,true);
		$criteria->compare('Plz',$this->Plz,true);
		$criteria->compare('Ort',$this->Ort,true);
		$criteria->compare('eMail1',$this->eMail1,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('kommentar',$this->kommentar,true);
		$criteria->compare('Anrede',$this->Anrede,true);
		$criteria->compare('k1',$this->k1,true);
		$criteria->compare('k2',$this->k2,true);
		$criteria->compare('k3',$this->k3,true);
		$criteria->compare('k4',$this->k4,true);
		$criteria->compare('k5',$this->k5,true);
		$criteria->compare('k1typ',$this->k1typ,true);
		$criteria->compare('k2typ',$this->k2typ,true);
		$criteria->compare('k3typ',$this->k3typ,true);
		$criteria->compare('k4typ',$this->k4typ,true);
		$criteria->compare('k5typ',$this->k5typ,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}