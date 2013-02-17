<?php

/**
 * This is the model class for table "Foto".
 *
 * The followings are the available columns in table 'Foto':
 * @property integer $IDPflanze
 * @property string $IDFoto
 * @property string $Bildbeschreibung
 * @property integer $FotoBlüh
 * @property integer $FotoFrucht
 * @property integer $FotoHabitus
 */
class Foto extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Foto the static model class
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
		return 'Foto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDPflanze, FotoBlüh, FotoFrucht, FotoHabitus', 'numerical', 'integerOnly'=>true),
			array('IDFoto, Bildbeschreibung', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDPflanze, IDFoto, Bildbeschreibung, FotoBlüh, FotoFrucht, FotoHabitus', 'safe', 'on'=>'search'),
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
			'IDFoto' => 'Idfoto',
			'Bildbeschreibung' => 'Bildbeschreibung',
			'FotoBlüh' => 'Foto Blüh',
			'FotoFrucht' => 'Foto Frucht',
			'FotoHabitus' => 'Foto Habitus',
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
		$criteria->compare('IDFoto',$this->IDFoto,true);
		$criteria->compare('Bildbeschreibung',$this->Bildbeschreibung,true);
		$criteria->compare('FotoBlüh',$this->FotoBlüh);
		$criteria->compare('FotoFrucht',$this->FotoFrucht);
		$criteria->compare('FotoHabitus',$this->FotoHabitus);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}