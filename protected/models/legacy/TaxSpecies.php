<?php

/**
 * This is the model class for table "tbl_tax_species".
 *
 * The followings are the available columns in table 'tbl_tax_species':
 * @property integer $tax_rankID
 * @property integer $basID
 * @property integer $taxonID
 * @property integer $synID
 * @property integer $statusID
 * @property integer $genID
 * @property integer $speciesID
 * @property integer $authorID
 * @property integer $subspeciesID
 * @property integer $subspecies_authorID
 * @property integer $varietyID
 * @property integer $variety_authorID
 * @property integer $subvarietyID
 * @property integer $subvariety_authorID
 * @property integer $formaID
 * @property integer $forma_authorID
 * @property integer $subformaID
 * @property integer $subforma_authorID
 * @property string $annotation
 * @property string $IPNItax_IDfk
 * @property string $IPNI_version
 * @property string $API_taxID_fk
 * @property string $tropicos_taxID_fk
 * @property string $linn_taxID_fk
 * @property integer $locked
 * @property integer $external
 * @property integer $externalID
 */
class TaxSpecies extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_tax_species';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tax_rankID, basID, synID, statusID, genID, speciesID, authorID, subspeciesID, subspecies_authorID, varietyID, variety_authorID, subvarietyID, subvariety_authorID, formaID, forma_authorID, subformaID, subforma_authorID, locked, external, externalID', 'numerical', 'integerOnly'=>true),
			array('IPNItax_IDfk, API_taxID_fk, tropicos_taxID_fk, linn_taxID_fk', 'length', 'max'=>50),
			array('IPNI_version', 'length', 'max'=>25),
			array('annotation', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tax_rankID, basID, taxonID, synID, statusID, genID, speciesID, authorID, subspeciesID, subspecies_authorID, varietyID, variety_authorID, subvarietyID, subvariety_authorID, formaID, forma_authorID, subformaID, subforma_authorID, annotation, IPNItax_IDfk, IPNI_version, API_taxID_fk, tropicos_taxID_fk, linn_taxID_fk, locked, external, externalID', 'safe', 'on'=>'search'),
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
			'tax_rankID' => 'Tax Rank',
			'basID' => 'Bas',
			'taxonID' => 'Taxon',
			'synID' => 'Syn',
			'statusID' => 'Status',
			'genID' => 'Gen',
			'speciesID' => 'Species',
			'authorID' => 'Author',
			'subspeciesID' => 'Subspecies',
			'subspecies_authorID' => 'Subspecies Author',
			'varietyID' => 'Variety',
			'variety_authorID' => 'Variety Author',
			'subvarietyID' => 'Subvariety',
			'subvariety_authorID' => 'Subvariety Author',
			'formaID' => 'Forma',
			'forma_authorID' => 'Forma Author',
			'subformaID' => 'Subforma',
			'subforma_authorID' => 'Subforma Author',
			'annotation' => 'Annotation',
			'IPNItax_IDfk' => 'Ipnitax Idfk',
			'IPNI_version' => 'Ipni Version',
			'API_taxID_fk' => 'Api Tax Id Fk',
			'tropicos_taxID_fk' => 'Tropicos Tax Id Fk',
			'linn_taxID_fk' => 'Linn Tax Id Fk',
			'locked' => 'Locked',
			'external' => 'External',
			'externalID' => 'External',
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

		$criteria->compare('tax_rankID',$this->tax_rankID);
		$criteria->compare('basID',$this->basID);
		$criteria->compare('taxonID',$this->taxonID);
		$criteria->compare('synID',$this->synID);
		$criteria->compare('statusID',$this->statusID);
		$criteria->compare('genID',$this->genID);
		$criteria->compare('speciesID',$this->speciesID);
		$criteria->compare('authorID',$this->authorID);
		$criteria->compare('subspeciesID',$this->subspeciesID);
		$criteria->compare('subspecies_authorID',$this->subspecies_authorID);
		$criteria->compare('varietyID',$this->varietyID);
		$criteria->compare('variety_authorID',$this->variety_authorID);
		$criteria->compare('subvarietyID',$this->subvarietyID);
		$criteria->compare('subvariety_authorID',$this->subvariety_authorID);
		$criteria->compare('formaID',$this->formaID);
		$criteria->compare('forma_authorID',$this->forma_authorID);
		$criteria->compare('subformaID',$this->subformaID);
		$criteria->compare('subforma_authorID',$this->subforma_authorID);
		$criteria->compare('annotation',$this->annotation,true);
		$criteria->compare('IPNItax_IDfk',$this->IPNItax_IDfk,true);
		$criteria->compare('IPNI_version',$this->IPNI_version,true);
		$criteria->compare('API_taxID_fk',$this->API_taxID_fk,true);
		$criteria->compare('tropicos_taxID_fk',$this->tropicos_taxID_fk,true);
		$criteria->compare('linn_taxID_fk',$this->linn_taxID_fk,true);
		$criteria->compare('locked',$this->locked);
		$criteria->compare('external',$this->external);
		$criteria->compare('externalID',$this->externalID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->dbHerbarInput;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TaxSpecies the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
