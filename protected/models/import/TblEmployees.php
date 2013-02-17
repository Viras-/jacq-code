<?php

/**
 * This is the model class for table "tblEmployees".
 *
 * The followings are the available columns in table 'tblEmployees':
 * @property integer $EmployeeID
 * @property string $EmpLastName
 * @property string $EmpFirstName
 * @property string $EmpNumber
 * @property string $EmpPassword
 */
class TblEmployees extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TblEmployees the static model class
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
		return 'tblEmployees';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('EmpLastName, EmpFirstName, EmpNumber', 'length', 'max'=>50),
			array('EmpPassword', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('EmployeeID, EmpLastName, EmpFirstName, EmpNumber, EmpPassword', 'safe', 'on'=>'search'),
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
			'EmployeeID' => 'Employee',
			'EmpLastName' => 'Emp Last Name',
			'EmpFirstName' => 'Emp First Name',
			'EmpNumber' => 'Emp Number',
			'EmpPassword' => 'Emp Password',
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

		$criteria->compare('EmployeeID',$this->EmployeeID);
		$criteria->compare('EmpLastName',$this->EmpLastName,true);
		$criteria->compare('EmpFirstName',$this->EmpFirstName,true);
		$criteria->compare('EmpNumber',$this->EmpNumber,true);
		$criteria->compare('EmpPassword',$this->EmpPassword,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}