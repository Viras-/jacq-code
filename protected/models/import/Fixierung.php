<?php

/**
 * This is the model class for table "Fixierung".
 *
 * The followings are the available columns in table 'Fixierung':
 * @property integer $IDPflanze
 * @property string $Sammler
 * @property string $SammelNr
 * @property string $Sammeldatum
 * @property string $Sammelort
 * @property integer $FixMorph
 * @property integer $Fixhblüh
 * @property integer $Fixfrucht
 * @property integer $Chrom
 * @property integer $DNASilika
 */
class Fixierung extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Fixierung the static model class
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
		return 'Fixierung';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDPflanze, FixMorph, Fixhblüh, Fixfrucht, Chrom, DNASilika', 'numerical', 'integerOnly'=>true),
			array('Sammler', 'length', 'max'=>150),
			array('SammelNr, Sammelort', 'length', 'max'=>255),
			array('Sammeldatum', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDPflanze, Sammler, SammelNr, Sammeldatum, Sammelort, FixMorph, Fixhblüh, Fixfrucht, Chrom, DNASilika', 'safe', 'on'=>'search'),
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
			'Sammler' => 'Sammler',
			'SammelNr' => 'Sammel Nr',
			'Sammeldatum' => 'Sammeldatum',
			'Sammelort' => 'Sammelort',
			'FixMorph' => 'Fix Morph',
			'Fixhblüh' => 'Fixhblüh',
			'Fixfrucht' => 'Fixfrucht',
			'Chrom' => 'Chrom',
			'DNASilika' => 'Dnasilika',
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
		$criteria->compare('Sammler',$this->Sammler,true);
		$criteria->compare('SammelNr',$this->SammelNr,true);
		$criteria->compare('Sammeldatum',$this->Sammeldatum,true);
		$criteria->compare('Sammelort',$this->Sammelort,true);
		$criteria->compare('FixMorph',$this->FixMorph);
		$criteria->compare('Fixhblüh',$this->Fixhblüh);
		$criteria->compare('Fixfrucht',$this->Fixfrucht);
		$criteria->compare('Chrom',$this->Chrom);
		$criteria->compare('DNASilika',$this->DNASilika);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}