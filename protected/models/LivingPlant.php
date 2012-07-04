<?php

/**
 * This is the model class for table "tbl_living_plant".
 *
 * The followings are the available columns in table 'tbl_living_plant':
 * @property integer $id
 * @property integer $garden_site_id
 * @property string $accession_number
 * @property string $ipen_number
 * @property integer $phyto_control
 * @property integer $tree_record_id
 * @property string $phyto_sanitary_product_number
 *
 * The followings are the available model relations:
 * @property CitesNumber[] $citesNumbers
 * @property BotanicalObject $id0
 * @property GardenSite $gardenSite
 * @property TreeRecord $treeRecord
 * @property Relevancy[] $relevancies
 */
class LivingPlant extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LivingPlant the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function init() {
            parent::init();
            
            if( $this->isNewRecord ) {
                $this->accession_number = date( "Y" ) . '-00000-001';
                $this->ipen_number = "XX-X-WU-" . $this->accession_number;
            }
        }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_living_plant';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('garden_site_id, phyto_control, tree_record_id', 'numerical', 'integerOnly'=>true),
			array('accession_number, ipen_number, phyto_sanitary_product_number', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, garden_site_id, accession_number, ipen_number, phyto_control, tree_record_id, phyto_sanitary_product_number', 'safe', 'on'=>'search'),
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
			'citesNumbers' => array(self::HAS_MANY, 'CitesNumber', 'livingplant_id'),
			'id0' => array(self::BELONGS_TO, 'BotanicalObject', 'id'),
			'gardenSite' => array(self::BELONGS_TO, 'GardenSite', 'garden_site_id'),
			'treeRecord' => array(self::BELONGS_TO, 'TreeRecord', 'tree_record_id'),
			'relevancies' => array(self::HAS_MANY, 'Relevancy', 'livingplant_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'garden_site_id' => 'Garden Site',
			'accession_number' => 'Accession Number',
			'ipen_number' => 'Ipen Number',
			'phyto_control' => 'Phyto Control',
			'tree_record_id' => 'Tree Record',
			'phyto_sanitary_product_number' => 'Phyto Sanitary Product Number',
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
		$criteria->compare('garden_site_id',$this->garden_site_id);
		$criteria->compare('accession_number',$this->accession_number,true);
		$criteria->compare('ipen_number',$this->ipen_number,true);
		$criteria->compare('phyto_control',$this->phyto_control);
		$criteria->compare('tree_record_id',$this->tree_record_id);
		$criteria->compare('phyto_sanitary_product_number',$this->phyto_sanitary_product_number,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
