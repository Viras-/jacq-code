<?php

/**
 * This is the model class for table "Plandaten".
 *
 * The followings are the available columns in table 'Plandaten':
 * @property integer $IDPflanze
 * @property string $Plan
 * @property integer $PosX
 * @property integer $PosY
 * @property integer $MarkX
 * @property integer $MarkY
 */
class Plandaten extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Plandaten the static model class
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
		return 'Plandaten';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDPflanze, PosX, PosY, MarkX, MarkY', 'numerical', 'integerOnly'=>true),
			array('Plan', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDPflanze, Plan, PosX, PosY, MarkX, MarkY', 'safe', 'on'=>'search'),
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
			'Plan' => 'Plan',
			'PosX' => 'Pos X',
			'PosY' => 'Pos Y',
			'MarkX' => 'Mark X',
			'MarkY' => 'Mark Y',
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
		$criteria->compare('Plan',$this->Plan,true);
		$criteria->compare('PosX',$this->PosX);
		$criteria->compare('PosY',$this->PosY);
		$criteria->compare('MarkX',$this->MarkX);
		$criteria->compare('MarkY',$this->MarkY);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}