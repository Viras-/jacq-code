<?php

/**
 * This is the model class for table "tbl_living_plant_tree_record_file_page".
 *
 * The followings are the available columns in table 'tbl_living_plant_tree_record_file_page':
 * @property integer $id
 * @property integer $living_plant_id
 * @property integer $tree_record_file_page_id
 * @property integer $corrections_done
 * @property string $corrections_date
 *
 * The followings are the available model relations:
 * @property TreeRecordFilePage $treeRecordFilePage
 * @property LivingPlant $livingPlant
 */
class LivingPlantTreeRecordFilePage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LivingPlantTreeRecordFilePage the static model class
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
		return 'tbl_living_plant_tree_record_file_page';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('living_plant_id, tree_record_file_page_id', 'required'),
			array('living_plant_id, tree_record_file_page_id, corrections_done', 'numerical', 'integerOnly'=>true),
			array('corrections_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, living_plant_id, tree_record_file_page_id, corrections_done, corrections_date', 'safe', 'on'=>'search'),
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
			'treeRecordFilePage' => array(self::BELONGS_TO, 'TreeRecordFilePage', 'tree_record_file_page_id'),
			'livingPlant' => array(self::BELONGS_TO, 'LivingPlant', 'living_plant_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'living_plant_id' => 'Living Plant',
			'tree_record_file_page_id' => 'Tree Record File Page',
			'corrections_done' => 'Corrections Done',
			'corrections_date' => 'Corrections Date',
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
		$criteria->compare('living_plant_id',$this->living_plant_id);
		$criteria->compare('tree_record_file_page_id',$this->tree_record_file_page_id);
		$criteria->compare('corrections_done',$this->corrections_done);
		$criteria->compare('corrections_date',$this->corrections_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}