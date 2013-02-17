<?php

/**
 * This is the model class for table "Revier".
 *
 * The followings are the available columns in table 'Revier':
 * @property integer $IDRevier
 * @property integer $IDGärtner
 * @property string $Revierbezeichnung
 * @property string $Unterabteilung
 * @property integer $Freiland
 * @property integer $Glashaus
 * @property string $EmpNumber
 */
class Revier extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Revier the static model class
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
		return 'Revier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDGärtner, Freiland, Glashaus', 'numerical', 'integerOnly'=>true),
			array('Revierbezeichnung, Unterabteilung, EmpNumber', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDRevier, IDGärtner, Revierbezeichnung, Unterabteilung, Freiland, Glashaus, EmpNumber', 'safe', 'on'=>'search'),
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
			'IDRevier' => 'Idrevier',
			'IDGärtner' => 'Idgärtner',
			'Revierbezeichnung' => 'Revierbezeichnung',
			'Unterabteilung' => 'Unterabteilung',
			'Freiland' => 'Freiland',
			'Glashaus' => 'Glashaus',
			'EmpNumber' => 'Emp Number',
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

		$criteria->compare('IDRevier',$this->IDRevier);
		$criteria->compare('IDGärtner',$this->IDGärtner);
		$criteria->compare('Revierbezeichnung',$this->Revierbezeichnung,true);
		$criteria->compare('Unterabteilung',$this->Unterabteilung,true);
		$criteria->compare('Freiland',$this->Freiland);
		$criteria->compare('Glashaus',$this->Glashaus);
		$criteria->compare('EmpNumber',$this->EmpNumber,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}