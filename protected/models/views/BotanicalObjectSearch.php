<?php

/**
 * This is the model class for table "view_botanical_object_search".
 *
 * The followings are the available columns in table 'view_botanical_object_search':
 * @property integer $id
 * @property integer $derivative_id
 * @property integer $scientific_name_id
 * @property string $genus
 * @property string $epithet
 * @property integer $organisation_id
 * @property string $organisation_description
 * @property string $location
 * @property string $place_number
 * @property integer $separated
 * @property integer $index_seminum
 * @property integer $greenhouse
 * @property string $accession_number
 * @property string $vegetative_count
 * @property string $type
 *
 * The followings are the available model relations:
 * @property BotanicalObject $id0
 * @property ImportProperties $importProperties
 * @property AlternativeAccessionNumber[] $alternativeAccessionNumbers
 */
class BotanicalObjectSearch extends CActiveRecord {

    /**
     * Helper attributes for searching across relations
     */
    public $scientificName_search;
    public $organisation_search;
    public $location_search;
    public $separated_search;
    public $label_type_search;
    public $organisation_hierarchy_search;
    public $scientificName_hierarchy_search;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'view_botanical_object_search';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, derivative_id, scientific_name_id, organisation_id, separated, index_seminum, greenhouse, accession_number', 'numerical', 'integerOnly' => true),
            array('genus', 'length', 'max' => 100),
            array('epithet', 'length', 'max' => 50),
            array('organisation_description', 'length', 'max' => 255),
            array('place_number', 'length', 'max' => 20),
            array('vegetative_count', 'length', 'max' => 21),
            array('type', 'length', 'max' => 10),
            array('location', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('scientificName_hierarchy_search, organisation_hierarchy_search, scientificName_search, organisation_search, location_search, separated_search, id, derivative_id, scientific_name_id, genus, epithet, organisation_id, organisation_description, location, place_number, separated, index_seminum, greenhouse, accession_number, vegetative_count, type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'id0' => array(self::BELONGS_TO, 'BotanicalObject', 'id'),
            'importProperties' => array(self::HAS_ONE, 'ImportProperties', 'botanical_object_id'),
            'alternativeAccessionNumbers' => array(self::HAS_MANY, 'AlternativeAccessionNumber', 'living_plant_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'derivative_id' => Yii::t('jacq', 'Derivative'),
            'scientific_name_id' => Yii::t('jacq', 'Scientific Name'),
            'genus' => Yii::t('jacq', 'Genus'),
            'epithet' => Yii::t('jacq', 'Epithet'),
            'organisation_id' => Yii::t('jacq', 'Organisation'),
            'organisation_description' => Yii::t('jacq', 'Organisation Description'),
            'location' => Yii::t('jacq', 'Location'),
            'separated' => Yii::t('jacq', 'Separated'),
            'greenhouse' => Yii::t('jacq', 'Greenhouse'),
            'accession_number' => Yii::t('jacq', 'Accession Number'),
            'vegetative_count' => Yii::t('jacq', 'Vegetative Count'),
            'type' => Yii::t('jacq', 'Type'),
            'id' => Yii::t('jacq', 'ID'),
            'place_number' => Yii::t('jacq', 'Place Number'),
            'index_seminum' => Yii::t('jacq', 'Index Seminum'),
            'scientificName_search' => Yii::t('jacq', 'Scientific Name'),
            'organisation_search' => Yii::t('jacq', 'Garden Site'),
            'location_search' => Yii::t('jacq', 'Location'),
            'separated_search' => Yii::t('jacq', 'Separated'),
            'organisation_hierarchy_search' => Yii::t('jacq', 'Hierarchie'),
            'scientificName_hierarchy_search' => Yii::t('jacq', 'Hierarchie')
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
    public function search() {
        // split scientific name search string into components
        $scientificName_searchComponents = explode(' ', $this->scientificName_search);

        $criteria = new CDbCriteria;
        $criteria->with = array('importProperties', 'id0.organisation' /* 'id0', 'id0.acquisitionEvent.location', 'id0.viewTaxon', 'id0.importProperties' */);
        $criteria->together = true;

        // check if we should use the hierarchy search for scientific names
        if ($this->scientificName_hierarchy_search == 1) {
            $scientific_name_ids = array();

            // search for scientific name
            $scientificNameSearchCriteria = new CDbCriteria();
            $scientificNameSearchCriteria->addSearchCondition('genus', $scientificName_searchComponents[0]);
            if (count($scientificName_searchComponents) >= 2) {
                $scientificNameSearchCriteria->addSearchCondition('epithet', $scientificName_searchComponents[1]);
            }
            $models_viewTaxon = ViewTaxon::model()->findAll($scientificNameSearchCriteria);

            // iterate over scientific name matches and try to find entry in classification
            foreach ($models_viewTaxon as $model_viewTaxon) {
                $scientific_name_ids[] = $model_viewTaxon->taxonID;

                // find synonymy entry
                $reference_id = Yii::app()->params['familyClassificationIds'][0];

                // get tax synonymy entry
                $model_taxSynonymy = TaxSynonymy::model()->findByAttributes(array(
                    'taxonID' => $model_viewTaxon->taxonID,
                    'source_citationID' => $reference_id
                ));

                // now fetch all children for the given classification and add it to the search list
                if ($model_taxSynonymy != NULL) {
                    $models_taxSynonymyChildren = $model_taxSynonymy->getAllChildren();

                    foreach ($models_taxSynonymyChildren as $model_taxSynonymyChildren) {
                        $scientific_name_ids[] = $model_taxSynonymyChildren->taxonID;

                        // since species entries are often not linked via classifications, we do this implicitly using the genID logic
                        if ($model_taxSynonymyChildren->viewTaxon->tax_rankID == 7) {
                            // find all scientific names with genID equal to our genus entry
                            $models_taxSpecies = TaxSpecies::model()->findAllByAttributes(array(
                                'genID' => $model_taxSynonymyChildren->viewTaxon->genID
                            ));

                            foreach ($models_taxSpecies as $model_taxSpecies) {
                                $scientific_name_ids[] = $model_taxSpecies->taxonID;
                            }
                        }
                    }
                }
            }

            $criteria->addInCondition('id0.scientific_name_id', $scientific_name_ids);
        }
        else {
            // search for scientific name
            $criteria->compare('genus', $scientificName_searchComponents[0], true);
            if (count($scientificName_searchComponents) >= 2) {
                $criteria->compare('epithet', $scientificName_searchComponents[1], true);
            }
            // search in imported scientific names
            if (!empty($this->scientificName_search)) {
                $criteria->addCondition("id0.scientific_name_id = " . Yii::app()->params['indetScientificNameId'] . " AND importProperties.species_name LIKE '%" . implode('%', $scientificName_searchComponents) . "%'", "OR");
            }
        }

        // check if we should search for the organisational hierarchy
        if ($this->organisation_hierarchy_search == 1) {
            // find all entries matching the name
            $organisationSearchCriteria = new CDbCriteria();
            $organisationSearchCriteria->addSearchCondition('description', $this->organisation_search);

            $models_organisationSearch = Organisation::model()->findAll($organisationSearchCriteria);

            // create a list of all relevant ids
            $organisationSearch_ids = array();
            foreach ($models_organisationSearch as $model_organisationSearch) {
                $organisationSearch_ids[] = $model_organisationSearch->id;
                $organisationSearch_ids = array_merge($organisationSearch_ids, $model_organisationSearch->getAllSubOrganisationIds());
            }

            $criteria->addInCondition('organisation.id', $organisationSearch_ids);
        }
        // else just match the name
        else {
            $criteria->compare('organisation_description', $this->organisation_search, true);
        }

        // add all other search criterias
        $criteria->compare('location', $this->location_search, true);
        $criteria->compare('place_number', $this->place_number, true);

        // prepare searching for (alternative) accession numbers
        if (!empty($this->accession_number)) {
            $accessionNumberCriteria = new CDbCriteria();
            $accessionNumberCriteria->with = array('alternativeAccessionNumbers');
            $accessionNumberCriteria->compare('accession_number', $this->accession_number, true, 'OR');
            $accessionNumberCriteria->compare('alternativeAccessionNumbers.number', $this->accession_number, true, 'OR');

            // add accession number searching to main criteria
            $criteria->with[] = 'alternativeAccessionNumbers';
            $criteria->mergeWith($accessionNumberCriteria, 'AND');
        }

        // search for separated entries
        if ($this->separated_search == null) {
            $this->separated_search = 0;
        }
        $criteria->compare('t.separated', $this->separated_search);

        // search for index seminum entries
        if ($this->index_seminum == 1) {
            $criteria->compare('index_seminum', $this->index_seminum);
        }

        // search for label by their types
        if (is_array($this->label_type_search)) {
            // criteria separate criteria for label searching
            $labelCriteria = new CDbCriteria();
            $labelCriteria->with = array('id0.tblLabelTypes');
            foreach ($this->label_type_search as $label_type) {
                $labelCriteria->compare('tblLabelTypes.label_type_id', $label_type, false, 'OR');
            }

            // add label search to main criteria
            $criteria->with[] = 'id0.tblLabelTypes';
            $criteria->mergeWith($labelCriteria, 'AND');
        }

        // check if the user is allowed to view plants from the greenhouse
        if (!Yii::app()->user->checkAccess('acs_greenhouse')) {
            $criteria->compare('greenhouse', 0);
        }

        // allow searching for type
        $criteria->compare('type', $this->type);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'attributes' => array(
                    'scientificName_search' => array(
                        'asc' => '`herbar_view`.GetScientificName(`id0`.`scientific_name_id`, 0)',
                        'desc' => '`herbar_view`.GetScientificName(`id0`.`scientific_name_id`, 0) DESC'
                    ),
                    'organisation_search' => array(
                        'asc' => 'organisation.description',
                        'desc' => 'organisation.description DESC'
                    ),
                    'location_search' => array(
                        'asc' => 'location.location',
                        'desc' => 'location.location DESC'
                    ),
                    '*'
                )
            )
        ));
    }

    public function primaryKey() {
        return array('id', 'derivative_id');
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BotanicalObjectSearch the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
