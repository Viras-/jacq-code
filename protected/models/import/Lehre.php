<?php

/**
 * This is the model class for table "Lehre".
 *
 * The followings are the available columns in table 'Lehre':
 * @property integer $IDArt
 * @property string $LV
 * @property string $Monat
 * @property string $Verantwortliche
 * @property string $Abteilung
 * @property string $Vorbereitung
 * @property string $Memo
 */
class Lehre extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lehre the static model class
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
		return 'Lehre';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDArt', 'numerical', 'integerOnly'=>true),
			array('LV, Verantwortliche, Abteilung', 'length', 'max'=>255),
			array('Monat, Vorbereitung, Memo', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDArt, LV, Monat, Verantwortliche, Abteilung, Vorbereitung, Memo', 'safe', 'on'=>'search'),
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
			'LV' => 'Lv',
			'Monat' => 'Monat',
			'Verantwortliche' => 'Verantwortliche',
			'Abteilung' => 'Abteilung',
			'Vorbereitung' => 'Vorbereitung',
			'Memo' => 'Memo',
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
		$criteria->compare('LV',$this->LV,true);
		$criteria->compare('Monat',$this->Monat,true);
		$criteria->compare('Verantwortliche',$this->Verantwortliche,true);
		$criteria->compare('Abteilung',$this->Abteilung,true);
		$criteria->compare('Vorbereitung',$this->Vorbereitung,true);
		$criteria->compare('Memo',$this->Memo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}