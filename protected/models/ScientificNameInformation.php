<?php

/**
 * This is the model class for table "tbl_scientific_name_information".
 *
 * The followings are the available columns in table 'tbl_scientific_name_information':
 * @property integer $scientific_name_id
 * @property string $spatial_distribution
 * @property string $variety
 * @property string $common_names
 *
 * The followings are the available model relations:
 * @property BotanicalObject[] $botanicalObjects
 */
class ScientificNameInformation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ScientificNameInformation the static model class
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
		return 'tbl_scientific_name_information';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('scientific_name_id', 'required'),
			array('scientific_name_id', 'numerical', 'integerOnly'=>true),
			array('variety', 'length', 'max'=>255),
                        array('spatial_distribution, common_names', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('scientific_name_id, spatial_distribution, variety, common_names', 'safe', 'on'=>'search'),
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
			'botanicalObjects' => array(self::HAS_MANY, 'BotanicalObject', 'scientific_name_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'scientific_name_id' => Yii::t('jacq', 'Scientific Name'),
			'spatial_distribution' => Yii::t('jacq', 'Spatial Distribution'),
			'variety' => Yii::t('jacq', 'Variety'),
                        'common_names' => Yii::t('jacq', 'Common Names'),
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

		$criteria->compare('scientific_name_id',$this->scientific_name_id);
		$criteria->compare('spatial_distribution',$this->spatial_distribution,true);
		$criteria->compare('variety',$this->variety,true);
                $criteria->compare('common_names',$this->common_names,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}