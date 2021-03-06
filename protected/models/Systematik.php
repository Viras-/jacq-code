<?php

/**
 * This is the model class for table "Systematik".
 *
 * The followings are the available columns in table 'Systematik':
 * @property integer $IDSys
 * @property string $Klasse
 * @property string $Familie
 * @property string $AbkFamilie
 * @property string $Gattung
 * @property string $Ersteller
 * @property string $Erstelldatum
 */
class Systematik extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Systematik the static model class
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
		return 'Systematik';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDSys, Klasse, Familie, AbkFamilie', 'required'),
			array('IDSys', 'numerical', 'integerOnly'=>true),
			array('Klasse, Familie, AbkFamilie, Gattung, Ersteller, Erstelldatum', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDSys, Klasse, Familie, AbkFamilie, Gattung, Ersteller, Erstelldatum', 'safe', 'on'=>'search'),
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
			'IDSys' => 'Idsys',
			'Klasse' => 'Klasse',
			'Familie' => 'Familie',
			'AbkFamilie' => 'Abk Familie',
			'Gattung' => 'Gattung',
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

		$criteria->compare('IDSys',$this->IDSys);
		$criteria->compare('Klasse',$this->Klasse,true);
		$criteria->compare('Familie',$this->Familie,true);
		$criteria->compare('AbkFamilie',$this->AbkFamilie,true);
		$criteria->compare('Gattung',$this->Gattung,true);
		$criteria->compare('Ersteller',$this->Ersteller,true);
		$criteria->compare('Erstelldatum',$this->Erstelldatum,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}