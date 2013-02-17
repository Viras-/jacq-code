<?php

/**
 * This is the model class for table "Akzession".
 *
 * The followings are the available columns in table 'Akzession':
 * @property integer $IDPflanze
 * @property integer $IDArt
 * @property integer $IDRevier
 * @property string $Freilandrevier
 * @property double $FreilandNr
 * @property string $AkzessNr
 * @property string $AkzessNr_alt
 * @property string $IPENNr
 * @property string $Eingangsdatum
 * @property string $Kulturhinweise
 * @property string $Anbaudatum
 * @property integer $Abgang
 * @property string $AbgangDatum
 * @property string $AbgangMemo
 * @property string $Bemerkungen
 * @property integer $ungeprüft
 * @property integer $Fixierung
 * @property integer $Herbarbeleg
 * @property integer $Foto
 * @property integer $det
 * @property string $detdat
 * @property string $detname
 * @property integer $CITES
 * @property integer $PHYTO
 * @property integer $CUSTOM
 * @property string $Ersteller
 * @property string $Erstelldatum
 * @property integer $Etikettendruck
 */
class Akzession extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Akzession the static model class
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
		return 'Akzession';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDArt, IDRevier, Abgang, ungeprüft, Fixierung, Herbarbeleg, Foto, det, CITES, PHYTO, CUSTOM, Etikettendruck', 'numerical', 'integerOnly'=>true),
			array('FreilandNr', 'numerical'),
			array('Freilandrevier, IPENNr, Eingangsdatum, detname', 'length', 'max'=>50),
			array('AkzessNr, AkzessNr_alt, Ersteller, Erstelldatum', 'length', 'max'=>255),
			array('Kulturhinweise, Anbaudatum, AbgangDatum, AbgangMemo, Bemerkungen, detdat', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDPflanze, IDArt, IDRevier, Freilandrevier, FreilandNr, AkzessNr, AkzessNr_alt, IPENNr, Eingangsdatum, Kulturhinweise, Anbaudatum, Abgang, AbgangDatum, AbgangMemo, Bemerkungen, ungeprüft, Fixierung, Herbarbeleg, Foto, det, detdat, detname, CITES, PHYTO, CUSTOM, Ersteller, Erstelldatum, Etikettendruck', 'safe', 'on'=>'search'),
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
			'IDArt' => 'Idart',
			'IDRevier' => 'Idrevier',
			'Freilandrevier' => 'Freilandrevier',
			'FreilandNr' => 'Freiland Nr',
			'AkzessNr' => 'Akzess Nr',
			'AkzessNr_alt' => 'Akzess Nr Alt',
			'IPENNr' => 'Ipennr',
			'Eingangsdatum' => 'Eingangsdatum',
			'Kulturhinweise' => 'Kulturhinweise',
			'Anbaudatum' => 'Anbaudatum',
			'Abgang' => 'Abgang',
			'AbgangDatum' => 'Abgang Datum',
			'AbgangMemo' => 'Abgang Memo',
			'Bemerkungen' => 'Bemerkungen',
			'ungeprüft' => 'Ungeprüft',
			'Fixierung' => 'Fixierung',
			'Herbarbeleg' => 'Herbarbeleg',
			'Foto' => 'Foto',
			'det' => 'Det',
			'detdat' => 'Detdat',
			'detname' => 'Detname',
			'CITES' => 'Cites',
			'PHYTO' => 'Phyto',
			'CUSTOM' => 'Custom',
			'Ersteller' => 'Ersteller',
			'Erstelldatum' => 'Erstelldatum',
			'Etikettendruck' => 'Etikettendruck',
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
		$criteria->compare('IDArt',$this->IDArt);
		$criteria->compare('IDRevier',$this->IDRevier);
		$criteria->compare('Freilandrevier',$this->Freilandrevier,true);
		$criteria->compare('FreilandNr',$this->FreilandNr);
		$criteria->compare('AkzessNr',$this->AkzessNr,true);
		$criteria->compare('AkzessNr_alt',$this->AkzessNr_alt,true);
		$criteria->compare('IPENNr',$this->IPENNr,true);
		$criteria->compare('Eingangsdatum',$this->Eingangsdatum,true);
		$criteria->compare('Kulturhinweise',$this->Kulturhinweise,true);
		$criteria->compare('Anbaudatum',$this->Anbaudatum,true);
		$criteria->compare('Abgang',$this->Abgang);
		$criteria->compare('AbgangDatum',$this->AbgangDatum,true);
		$criteria->compare('AbgangMemo',$this->AbgangMemo,true);
		$criteria->compare('Bemerkungen',$this->Bemerkungen,true);
		$criteria->compare('ungeprüft',$this->ungeprüft);
		$criteria->compare('Fixierung',$this->Fixierung);
		$criteria->compare('Herbarbeleg',$this->Herbarbeleg);
		$criteria->compare('Foto',$this->Foto);
		$criteria->compare('det',$this->det);
		$criteria->compare('detdat',$this->detdat,true);
		$criteria->compare('detname',$this->detname,true);
		$criteria->compare('CITES',$this->CITES);
		$criteria->compare('PHYTO',$this->PHYTO);
		$criteria->compare('CUSTOM',$this->CUSTOM);
		$criteria->compare('Ersteller',$this->Ersteller,true);
		$criteria->compare('Erstelldatum',$this->Erstelldatum,true);
		$criteria->compare('Etikettendruck',$this->Etikettendruck);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}