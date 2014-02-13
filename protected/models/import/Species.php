<?php

/**
 * This is the model class for table "Species".
 *
 * The followings are the available columns in table 'Species':
 * @property integer $IDArt
 * @property integer $IDSys
 * @property string $Art
 * @property string $Autor
 * @property string $UArt
 * @property string $UArtAutor
 * @property string $Var
 * @property string $VarAutor
 * @property string $FormCult
 * @property string $FormCultAutor
 */
class Species extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Species the static model class
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
		return 'Species';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDArt', 'required'),
			array('IDArt, IDSys', 'numerical', 'integerOnly'=>true),
			array('Art, Autor, UArt, UArtAutor, Var, VarAutor, FormCult, FormCultAutor', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IDArt, IDSys, Art, Autor, UArt, UArtAutor, Var, VarAutor, FormCult, FormCultAutor', 'safe', 'on'=>'search'),
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
			'IDArt' => 'Idart',
			'IDSys' => 'Idsys',
			'Art' => 'Art',
			'Autor' => 'Autor',
			'UArt' => 'Uart',
			'UArtAutor' => 'Uart Autor',
			'Var' => 'Var',
			'VarAutor' => 'Var Autor',
			'FormCult' => 'Form Cult',
			'FormCultAutor' => 'Form Cult Autor',
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

		$criteria->compare('IDArt',$this->IDArt);
		$criteria->compare('IDSys',$this->IDSys);
		$criteria->compare('Art',$this->Art,true);
		$criteria->compare('Autor',$this->Autor,true);
		$criteria->compare('UArt',$this->UArt,true);
		$criteria->compare('UArtAutor',$this->UArtAutor,true);
		$criteria->compare('Var',$this->Var,true);
		$criteria->compare('VarAutor',$this->VarAutor,true);
		$criteria->compare('FormCult',$this->FormCult,true);
		$criteria->compare('FormCultAutor',$this->FormCultAutor,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        /**
         * Helper function for constructing the scientific name
         * @return string
         */
        public function getScientificName() {
            $scientificName = "";

            // Find out the Genus
            $model_systematik = Systematik::model()->findByAttributes(array(
                'IDSys' => $this->IDSys
            ));
            
            // check for valid systematik entry
            if( $model_systematik == NULL ) {
                return "Invalid Systematik reference for '" . $this->IDArt . "'";
            }

            // try to clean the names
            $Art = $this->Art;
            $Art = preg_replace('/^sp(\.| )(.*)$/i', '$2', $Art);
            $Art = trim($Art);
            $UArt = $this->UArt;
            $UArt = preg_replace('/^ssp(\.| )(.*)$/i', '$2', $UArt);
            $UArt = preg_replace('/^subsp(\.| )(.*)$/i', '$2', $UArt);
            $UArt = trim($UArt);
            $Var = $this->Var;
            $Var = preg_replace('/^var(\.| )(.*)$/i', '$2', $Var);
            $Var = trim($Var);
            $FormCult = $this->FormCult;
            $FormCult = preg_replace('/^f(\.| )(.*)$/i', '$2', $FormCult);
            $FormCult = trim($FormCult);

            if( empty($Art) && empty($this->UArt) && empty($this->Var) && empty($this->FormCult) ) {
                $scientificName = $model_systematik->Gattung;
            }
            else {
                if( !empty($Art) ) {
                    $scientificName = $model_systematik->Gattung . ' ' . $Art . ' ' . $this->Autor;
                }
                if( !empty($UArt) ) {
                    $scientificName = $model_systematik->Gattung . ' ' . $Art . ' subsp. ' . $UArt . ' ' . $this->UArtAutor;
                }
                if( !empty($Var) ) {
                    $scientificName = $model_systematik->Gattung . ' ' . $Art . ' var. ' . $Var . ' ' . $this->VarAutor;
                }
                // check for forma / cult but ignore if it contains invalid characters
                if( !empty($FormCult) && stripos($FormCult, '`') === FALSE && stripos($FormCult, '\'') === FALSE ) {
                    $scientificName = $model_systematik->Gattung . ' ' . $Art . ' f. ' . $FormCult . ' ' . $this->FormCultAutor;
                }
            }

            // return complete scientifc name
            return $scientificName;
        }        
}
