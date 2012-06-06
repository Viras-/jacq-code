<?php

/**
 * This is the model class for table "jacq_input.tbl_tree_record".
 *
 * The followings are the available columns in table 'jacq_input.tbl_tree_record':
 * @property integer $id
 * @property integer $page
 * @property integer $tree_record_file_page_id
 *
 * The followings are the available model relations:
 * @property Livingplant[] $livingplants
 * @property TreeRecordFilePage $treeRecordFilePage
 */
class TreeRecord extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TreeRecord the static model class
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
		return 'jacq_input.tbl_tree_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, tree_record_file_page_id', 'required'),
			array('id, page, tree_record_file_page_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, page, tree_record_file_page_id', 'safe', 'on'=>'search'),
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
			'livingplants' => array(self::HAS_MANY, 'Livingplant', 'tree_record_id'),
			'treeRecordFilePage' => array(self::BELONGS_TO, 'TreeRecordFilePage', 'tree_record_file_page_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'page' => 'Page',
			'tree_record_file_page_id' => 'Tree Record File Page',
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
		$criteria->compare('page',$this->page);
		$criteria->compare('tree_record_file_page_id',$this->tree_record_file_page_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}