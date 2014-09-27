<?php

/**
 * This is the model class for table "tbl_index_seminum_revision".
 *
 * The followings are the available columns in table 'tbl_index_seminum_revision':
 * @property string $index_seminum_revision_id
 * @property integer $user_id
 * @property string $timestamp
 *
 * The followings are the available model relations:
 * @property IndexSeminumContent[] $indexSeminumContents
 * @property FrmwrkUser $user
 */
class IndexSeminumRevision extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_index_seminum_revision';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, timestamp', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('index_seminum_revision_id, user_id, timestamp', 'safe', 'on'=>'search'),
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
			'indexSeminumContents' => array(self::HAS_MANY, 'IndexSeminumContent', 'index_seminum_revision_id'),
			'user' => array(self::BELONGS_TO, 'FrmwrkUser', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'index_seminum_revision_id' => 'Index Seminum Revision',
			'user_id' => 'User',
			'timestamp' => 'Timestamp',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('index_seminum_revision_id',$this->index_seminum_revision_id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IndexSeminumRevision the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
