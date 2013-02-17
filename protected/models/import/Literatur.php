<?php

/**
 * This is the model class for table "Literatur".
 *
 * The followings are the available columns in table 'Literatur':
 * @property integer $IDSys
 * @property string $Autor
 * @property string $Titel
 * @property string $Journal
 * @property string $Band
 * @property string $Seiten
 * @property string $Buch
 * @property string $Hrsg
 */
class Literatur extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Literatur the static model class
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
		return 'Literatur';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDSys', 'numerical', 'integerOnly'=>true),
			array('Autor, Titel, Journal, Band, Seiten, Buch, Hrsg', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDSys, Autor, Titel, Journal, Band, Seiten, Buch, Hrsg', 'safe', 'on'=>'search'),
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
			'Autor' => 'Autor',
			'Titel' => 'Titel',
			'Journal' => 'Journal',
			'Band' => 'Band',
			'Seiten' => 'Seiten',
			'Buch' => 'Buch',
			'Hrsg' => 'Hrsg',
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
		$criteria->compare('Autor',$this->Autor,true);
		$criteria->compare('Titel',$this->Titel,true);
		$criteria->compare('Journal',$this->Journal,true);
		$criteria->compare('Band',$this->Band,true);
		$criteria->compare('Seiten',$this->Seiten,true);
		$criteria->compare('Buch',$this->Buch,true);
		$criteria->compare('Hrsg',$this->Hrsg,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}