<?php

/**
 * This is the model class for table "tblInventur".
 *
 * The followings are the available columns in table 'tblInventur':
 * @property integer $ID
 * @property integer $INV_standort
 * @property string $AkzessNr
 * @property integer $INV_stueckzahl
 * @property string $INV_datum
 */
class TblInventur extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TblInventur the static model class
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
		return 'tblInventur';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('INV_standort, INV_stueckzahl', 'numerical', 'integerOnly'=>true),
			array('AkzessNr', 'length', 'max'=>255),
			array('INV_datum', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, INV_standort, AkzessNr, INV_stueckzahl, INV_datum', 'safe', 'on'=>'search'),
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
			'ID' => 'ID',
			'INV_standort' => 'Inv Standort',
			'AkzessNr' => 'Akzess Nr',
			'INV_stueckzahl' => 'Inv Stueckzahl',
			'INV_datum' => 'Inv Datum',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('INV_standort',$this->INV_standort);
		$criteria->compare('AkzessNr',$this->AkzessNr,true);
		$criteria->compare('INV_stueckzahl',$this->INV_stueckzahl);
		$criteria->compare('INV_datum',$this->INV_datum,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}