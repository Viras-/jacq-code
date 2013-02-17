<?php

/**
 * This is the model class for table "tblBaumbewertung".
 *
 * The followings are the available columns in table 'tblBaumbewertung':
 * @property integer $IDPflanze
 * @property string $AkzessNr
 * @property integer $Freilandnr
 * @property integer $Höhe
 * @property integer $Stammumfang
 * @property integer $Kronendurchmesser
 * @property string $Standort
 * @property string $Sachwert
 * @property string $Historischer_Wert
 * @property string $Wissenschaftl_Wert
 * @property string $Bemerkungen
 */
class TblBaumbewertung extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TblBaumbewertung the static model class
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
		return 'tblBaumbewertung';
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
			array('IDPflanze, Freilandnr, Höhe, Stammumfang, Kronendurchmesser', 'numerical', 'integerOnly'=>true),
			array('AkzessNr, Standort', 'length', 'max'=>50),
			array('Sachwert, Historischer_Wert, Wissenschaftl_Wert', 'length', 'max'=>19),
			array('Bemerkungen', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDPflanze, AkzessNr, Freilandnr, Höhe, Stammumfang, Kronendurchmesser, Standort, Sachwert, Historischer_Wert, Wissenschaftl_Wert, Bemerkungen', 'safe', 'on'=>'search'),
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
			'AkzessNr' => 'Akzess Nr',
			'Freilandnr' => 'Freilandnr',
			'Höhe' => 'Höhe',
			'Stammumfang' => 'Stammumfang',
			'Kronendurchmesser' => 'Kronendurchmesser',
			'Standort' => 'Standort',
			'Sachwert' => 'Sachwert',
			'Historischer_Wert' => 'Historischer Wert',
			'Wissenschaftl_Wert' => 'Wissenschaftl Wert',
			'Bemerkungen' => 'Bemerkungen',
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
		$criteria->compare('AkzessNr',$this->AkzessNr,true);
		$criteria->compare('Freilandnr',$this->Freilandnr);
		$criteria->compare('Höhe',$this->Höhe);
		$criteria->compare('Stammumfang',$this->Stammumfang);
		$criteria->compare('Kronendurchmesser',$this->Kronendurchmesser);
		$criteria->compare('Standort',$this->Standort,true);
		$criteria->compare('Sachwert',$this->Sachwert,true);
		$criteria->compare('Historischer_Wert',$this->Historischer_Wert,true);
		$criteria->compare('Wissenschaftl_Wert',$this->Wissenschaftl_Wert,true);
		$criteria->compare('Bemerkungen',$this->Bemerkungen,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}