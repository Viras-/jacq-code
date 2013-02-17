<?php

/**
 * This is the model class for table "SAKSys".
 *
 * The followings are the available columns in table 'SAKSys':
 * @property integer $IDSAK
 * @property string $Klasse
 * @property string $Familie
 * @property string $Gattung
 * @property string $Art
 * @property string $Autor
 * @property string $UArt
 * @property string $UArtAutor
 * @property integer $plviv
 * @property integer $desicc
 */
class SAKSys extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SAKSys the static model class
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
		return 'SAKSys';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDSAK, plviv, desicc', 'numerical', 'integerOnly'=>true),
			array('Klasse, Familie, Gattung, Art, Autor, UArt, UArtAutor', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDSAK, Klasse, Familie, Gattung, Art, Autor, UArt, UArtAutor, plviv, desicc', 'safe', 'on'=>'search'),
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
			'IDSAK' => 'Idsak',
			'Klasse' => 'Klasse',
			'Familie' => 'Familie',
			'Gattung' => 'Gattung',
			'Art' => 'Art',
			'Autor' => 'Autor',
			'UArt' => 'Uart',
			'UArtAutor' => 'Uart Autor',
			'plviv' => 'Plviv',
			'desicc' => 'Desicc',
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

		$criteria->compare('IDSAK',$this->IDSAK);
		$criteria->compare('Klasse',$this->Klasse,true);
		$criteria->compare('Familie',$this->Familie,true);
		$criteria->compare('Gattung',$this->Gattung,true);
		$criteria->compare('Art',$this->Art,true);
		$criteria->compare('Autor',$this->Autor,true);
		$criteria->compare('UArt',$this->UArt,true);
		$criteria->compare('UArtAutor',$this->UArtAutor,true);
		$criteria->compare('plviv',$this->plviv);
		$criteria->compare('desicc',$this->desicc);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}