<?php

/**
 * This is the model class for table "tbl_tax_classification".
 *
 * The followings are the available columns in table 'tbl_tax_classification':
 * @property integer $classification_id
 * @property integer $tax_syn_ID
 * @property integer $parent_taxonID
 * @property string $number
 * @property integer $order
 *
 * The followings are the available model relations:
 * @property TaxSynonymy $taxSynonymy
 */
class TaxClassification extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TaxClassification the static model class
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
        return 'tbl_tax_classification';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tax_syn_ID, parent_taxonID', 'required'),
            array('tax_syn_ID, parent_taxonID, order', 'numerical', 'integerOnly' => true),
            array('number', 'length', 'max' => 15),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('classification_id, tax_syn_ID, parent_taxonID, number, order', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'taxSynonymy' => array(self::BELONGS_TO, 'TaxSynonymy', 'tax_syn_ID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'classification_id' => 'Classification',
            'tax_syn_ID' => 'Tax Syn',
            'parent_taxonID' => 'Parent Taxon',
            'number' => 'Number',
            'order' => 'Order',
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

        $criteria->compare('classification_id', $this->classification_id);
        $criteria->compare('tax_syn_ID', $this->tax_syn_ID);
        $criteria->compare('parent_taxonID', $this->parent_taxonID);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('order', $this->order);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    protected function getLowerScientificNameIds() {
        $scientific_name_ids = array();

        $criteria = new CDbCriteria;
        $criteria->with = array('taxSynonymy');
        $criteria->together = true;

        $criteria->compare('taxSynonymy.source_citationID', $this->taxSynonymy->source_citationID);
        $criteria->compare('parent_taxonID', $this->taxSynonymy->taxonID);

        $models_taxClassification = $this::model()->findAll($criteria);

        foreach ($models_taxClassification as $model_taxClassification) {

        }
    }

}
