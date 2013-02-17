<?php

/**
 * This is the model class for table "SysDiverses".
 *
 * The followings are the available columns in table 'SysDiverses':
 * @property integer $IDArt
 * @property string $DtName
 * @property string $Synonyme
 * @property integer $Sperrvermerk
 * @property string $Verbreitung
 * @property string $Wuchsform
 * @property integer $checked
 * @property string $checked_date
 * @property string $Ersteller
 * @property string $Erstelldatum
 */
class SysDiverses extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SysDiverses the static model class
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
		return 'SysDiverses';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDArt, Sperrvermerk, checked', 'numerical', 'integerOnly'=>true),
			array('DtName, Synonyme, Verbreitung, Wuchsform, Ersteller, Erstelldatum', 'length', 'max'=>255),
			array('checked_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDArt, DtName, Synonyme, Sperrvermerk, Verbreitung, Wuchsform, checked, checked_date, Ersteller, Erstelldatum', 'safe', 'on'=>'search'),
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
			'DtName' => 'Dt Name',
			'Synonyme' => 'Synonyme',
			'Sperrvermerk' => 'Sperrvermerk',
			'Verbreitung' => 'Verbreitung',
			'Wuchsform' => 'Wuchsform',
			'checked' => 'Checked',
			'checked_date' => 'Checked Date',
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
		$criteria->compare('DtName',$this->DtName,true);
		$criteria->compare('Synonyme',$this->Synonyme,true);
		$criteria->compare('Sperrvermerk',$this->Sperrvermerk);
		$criteria->compare('Verbreitung',$this->Verbreitung,true);
		$criteria->compare('Wuchsform',$this->Wuchsform,true);
		$criteria->compare('checked',$this->checked);
		$criteria->compare('checked_date',$this->checked_date,true);
		$criteria->compare('Ersteller',$this->Ersteller,true);
		$criteria->compare('Erstelldatum',$this->Erstelldatum,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}