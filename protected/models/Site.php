<?php

/**
 * This is the model class for table "jacq_input.tbl_site".
 *
 * The followings are the available columns in table 'jacq_input.tbl_site':
 * @property integer $id
 * @property string $description
 * @property string $department
 * @property integer $gardener
 * @property integer $greenhouse
 * @property integer $site_id
 *
 * The followings are the available model relations:
 * @property Livingplant[] $livingplants
 * @property Site $site
 * @property Site[] $sites
 */
class Site extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Site the static model class
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
		return 'jacq_input.tbl_site';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, site_id', 'required'),
			array('id, gardener, greenhouse, site_id', 'numerical', 'integerOnly'=>true),
			array('description, department', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, description, department, gardener, greenhouse, site_id', 'safe', 'on'=>'search'),
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
			'livingplants' => array(self::HAS_MANY, 'Livingplant', 'site_id'),
			'site' => array(self::BELONGS_TO, 'Site', 'site_id'),
			'sites' => array(self::HAS_MANY, 'Site', 'site_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'description' => 'Description',
			'department' => 'Department',
			'gardener' => 'Gardener',
			'greenhouse' => 'Greenhouse',
			'site_id' => 'Site',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('department',$this->department,true);
		$criteria->compare('gardener',$this->gardener);
		$criteria->compare('greenhouse',$this->greenhouse);
		$criteria->compare('site_id',$this->site_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}