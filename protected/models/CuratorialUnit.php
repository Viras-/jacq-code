<?php

/**
 * This is the model class for table "tbl_curatorial_unit".
 *
 * The followings are the available columns in table 'tbl_curatorial_unit':
 * @property integer $curatorial_unit_id
 * @property integer $curatorial_unit_type_id
 * @property string $inventory_number
 * @property string $acquisition_number
 * @property string $barcode
 * @property string $timestamp
 *
 * The followings are the available model relations:
 * @property CuratorialUnitType $curatorialUnitType
 * @property Specimen[] $specimens
 */
class CuratorialUnit extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_curatorial_unit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('curatorial_unit_type_id, timestamp', 'required'),
			array('curatorial_unit_type_id', 'numerical', 'integerOnly'=>true),
			array('inventory_number, acquisition_number', 'length', 'max'=>45),
			array('barcode', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('curatorial_unit_id, curatorial_unit_type_id, inventory_number, acquisition_number, barcode, timestamp', 'safe', 'on'=>'search'),
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
			'curatorialUnitType' => array(self::BELONGS_TO, 'CuratorialUnitType', 'curatorial_unit_type_id'),
			'specimens' => array(self::HAS_MANY, 'Specimen', 'curatorial_unit_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'curatorial_unit_id' => 'Curatorial Unit',
			'curatorial_unit_type_id' => 'Curatorial Unit Type',
			'inventory_number' => 'Inventory Number',
			'acquisition_number' => 'Acquisition Number',
			'barcode' => 'Barcode',
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

		$criteria->compare('curatorial_unit_id',$this->curatorial_unit_id);
		$criteria->compare('curatorial_unit_type_id',$this->curatorial_unit_type_id);
		$criteria->compare('inventory_number',$this->inventory_number,true);
		$criteria->compare('acquisition_number',$this->acquisition_number,true);
		$criteria->compare('barcode',$this->barcode,true);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CuratorialUnit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
