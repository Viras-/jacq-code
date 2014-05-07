<?php

/**
 * This is the model class for table "tbl_tax_synonymy".
 *
 * The followings are the available columns in table 'tbl_tax_synonymy':
 * @property integer $tax_syn_ID
 * @property integer $taxonID
 * @property integer $acc_taxon_ID
 * @property string $ref_date
 * @property integer $preferred_taxonomy
 * @property string $annotations
 * @property integer $locked
 * @property string $source
 * @property integer $source_citationID
 * @property integer $source_person_ID
 * @property integer $source_serviceID
 * @property integer $source_specimenID
 * @property integer $userID
 * @property string $timestamp
 * 
 * The followings are the available model relations:
 * @property ViewTaxon $viewTaxon
 * @property TaxClassification $taxClassification
 * @property TaxSynonymy $taxSynonymyAccepted
 * @property TaxSpecies $taxSpecies
 * @property TaxSpecies $acceptedTaxSpecies
 */
class TaxSynonymy extends CActiveRecord {

    /**
     * Reference to classification entry
     * @var TaxClassification
     */
    public $taxClassification = NULL;

    /**
     * Reference to accepted entry (if current is not accepted)
     * @var TaxSynonymy 
     */
    public $taxSynonymyAccepted = NULL;

    /**
     * Helper function for fetching the accepted entry
     * @return \TaxSynonymy TaxSynonymy entry which is accepted
     */
    public function getAccepted() {
        if ($this->taxSynonymyAccepted == NULL)
            return $this;

        // return the accepted name for our accepted taxon
        return $this->taxSynonymyAccepted->getAccepted();
    }

    /**
     * Helper function for fetching the parent of a given TaxSynonymy entry
     * @return null|\TaxSynonymy null if no classification found or parent entry not found, else the TaxSynonymy model
     */
    public function getParent() {
        if ($this->taxClassification === NULL || $this->taxClassification->parent_taxonID === NULL)
            return NULL;

        $model_parentTaxSynonymy = TaxSynonymy::model()->findByAttributes(array(
            'taxonID' => $this->taxClassification->parent_taxonID,
            'source_citationID' => $this->source_citationID,
        ));

        if ($model_parentTaxSynonymy == NULL)
            return NULL;

        // return the parent
        return $model_parentTaxSynonymy;
    }

    /**
     * Fetches all children for the current synonymy entry
     * NOTE: this function does not take care of checking for accepted entry
     * @return array
     */
    public function getChildren() {
        // fetch all classification entries containing ourself as parent entry
        $models_childrenTaxClassification = TaxClassification::model()->findAllByAttributes(array(
            'parent_taxonID' => $this->taxonID,
            'source_citationID' => $this->source_citationID,
        ));

        // fetch all fitting synonymy entries
        $models_childrenTaxSynonymy = array();
        foreach ($models_childrenTaxClassification as $model_childTaxClassification) {
            $models_childrenTaxSynonymy[] = $model_childTaxClassification->taxSynonymy;
        }

        // return list of synonymy entries
        return $models_childrenTaxClassification;
    }

    /**
     * Find the family entry
     * @return \TaxSynonymy
     */
    public function getFamily() {
        // check if this entry is family level
        if ($this->viewTaxon->isFamily())
            return $this;

        // if not fetch the parent and check if we have one
        $model_parentTaxSynonymy = $this->getParent();
        if ($model_parentTaxSynonymy == NULL)
            return NULL;

        // finally find the family of the parent entry
        return $this->getParent()->getFamily();
    }

    protected function afterFind() {
        parent::afterFind();

        $this->taxClassification = TaxClassification::model()->findByAttributes(array(
            'tax_syn_ID' => $this->tax_syn_ID
        ));

        $this->taxSynonymyAccepted = TaxSynonymy::model()->findByAttributes(array(
            'taxonID' => $this->acc_taxon_ID,
            'source_citationID' => $this->source_citationID
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TaxSynonymy the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return CDbConnection database connection
     */
    public function getDbConnection() {
        return Yii::app()->dbHerbarInput;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_tax_synonymy';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userID, timestamp', 'required'),
            array('taxonID, acc_taxon_ID, preferred_taxonomy, locked, source_citationID, source_person_ID, source_serviceID, source_specimenID, userID', 'numerical', 'integerOnly' => true),
            array('source', 'length', 'max' => 20),
            array('ref_date, annotations', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('tax_syn_ID, taxonID, acc_taxon_ID, ref_date, preferred_taxonomy, annotations, locked, source, source_citationID, source_person_ID, source_serviceID, source_specimenID, userID, timestamp', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'taxClassification' => array(self::HAS_ONE, 'TaxClassification', array('tax_syn_ID' => 'tax_syn_ID')),
            'taxSynonymyAccepted' => array(self::HAS_ONE, 'TaxSynonymy', array('acc_taxon_ID' => 'taxonID', 'source_citationID' => 'source_citationID')),
            'viewTaxon' => array(self::BELONGS_TO, 'ViewTaxon', 'taxonID'),
            'taxSpecies' => array(self::BELONGS_TO, 'TaxSpecies', 'taxonID'),
            'acceptedTaxSpecies' => array(self::BELONGS_TO, 'TaxSpecies', array('acc_taxon_ID' => 'taxonID')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'tax_syn_ID' => 'Tax Syn',
            'taxonID' => 'Taxon',
            'acc_taxon_ID' => 'Acc Taxon',
            'ref_date' => 'Ref Date',
            'preferred_taxonomy' => 'Preferred Taxonomy',
            'annotations' => 'Annotations',
            'locked' => 'Locked',
            'source' => 'Source',
            'source_citationID' => 'Source Citation',
            'source_person_ID' => 'Source Person',
            'source_serviceID' => 'Source Service',
            'source_specimenID' => 'Source Specimen',
            'userID' => 'User',
            'timestamp' => 'Timestamp',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('tax_syn_ID', $this->tax_syn_ID);
        $criteria->compare('taxonID', $this->taxonID);
        $criteria->compare('acc_taxon_ID', $this->acc_taxon_ID);
        $criteria->compare('ref_date', $this->ref_date, true);
        $criteria->compare('preferred_taxonomy', $this->preferred_taxonomy);
        $criteria->compare('annotations', $this->annotations, true);
        $criteria->compare('locked', $this->locked);
        $criteria->compare('source', $this->source, true);
        $criteria->compare('source_citationID', $this->source_citationID);
        $criteria->compare('source_person_ID', $this->source_person_ID);
        $criteria->compare('source_serviceID', $this->source_serviceID);
        $criteria->compare('source_specimenID', $this->source_specimenID);
        $criteria->compare('userID', $this->userID);
        $criteria->compare('timestamp', $this->timestamp, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
