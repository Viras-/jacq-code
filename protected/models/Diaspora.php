<?php

/**
 * This is the model class for table "jacq_input.tbl_diaspora".
 *
 * The followings are the available columns in table 'jacq_input.tbl_diaspora':
 * @property integer $id
 * @property integer $diaspora_bank_id
 *
 * The followings are the available model relations:
 * @property BotanicalObject $id0
 * @property DiasporaBank $diasporaBank
 */
class Diaspora extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Diaspora the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'jacq_input.tbl_diaspora';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, diaspora_bank_id', 'required'),
			array('id, diaspora_bank_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, diaspora_bank_id', 'safe', 'on'=>'search'),
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
			'id0' => array(self::BELONGS_TO, 'BotanicalObject', 'id'),
			'diasporaBank' => array(self::BELONGS_TO, 'DiasporaBank', 'diaspora_bank_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'diaspora_bank_id' => 'Diaspora Bank',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('diaspora_bank_id',$this->diaspora_bank_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}