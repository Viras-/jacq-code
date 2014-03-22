<?php

/**
 * This is the model class for table "tbl_organisation".
 *
 * The followings are the available columns in table 'tbl_organisation':
 * @property integer $id
 * @property string $description
 * @property string $department
 * @property integer $greenhouse
 * @property integer $parent_organisation_id
 * @property integer $gardener_id
 * @property string $ipen_code
 *
 * The followings are the available model relations:
 * @property BotanicalObject[] $botanicalObjects
 * @property ImageServer $imageServer
 * @property Organisation $parentOrganisation
 * @property Organisation[] $organisations
 * @property User $gardener
 */
class Organisation extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Organisation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_organisation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('greenhouse, parent_organisation_id, gardener_id', 'numerical', 'integerOnly' => true),
            array('description, department', 'length', 'max' => 255),
            array('ipen_code', 'length', 'max' => 5),
            // prevent parent organisation to be ourself
            array('parent_organisation_id', 'compare', 'compareAttribute' => 'id', 'operator' => '!='),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, description, department, greenhouse, parent_organisation_id, gardener_id, ipen_code', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'botanicalObjects' => array(self::HAS_MANY, 'BotanicalObject', 'organisation_id'),
            'imageServer' => array(self::HAS_ONE, 'ImageServer', 'organisation_id'),
            'parentOrganisation' => array(self::BELONGS_TO, 'Organisation', 'parent_organisation_id'),
            'organisations' => array(self::HAS_MANY, 'Organisation', 'parent_organisation_id'),
            'gardener' => array(self::BELONGS_TO, 'User', 'gardener_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'description' => Yii::t('jacq', 'Description'),
            'department' => Yii::t('jacq', 'Department'),
            'greenhouse' => Yii::t('jacq', 'Greenhouse'),
            'parent_organisation_id' => Yii::t('jacq', 'Parent Organisation'),
            'gardener_id' => Yii::t('jacq', 'Gardener'),
            'ipen_code' => Yii::t('jacq', 'Ipen Code'),
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

        $criteria->compare('id', $this->id);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('department', $this->department, true);
        $criteria->compare('greenhouse', $this->greenhouse);
        $criteria->compare('parent_organisation_id', $this->parent_organisation_id);
        $criteria->compare('gardener_id', $this->gardener_id);
        $criteria->compare('ipen_code', $this->ipen_code, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Return the IPEN code for this garden site (recursive parent search)
     * @return string IPEN code
     */
    public function getIpenCode() {
        // check if we have an individual ipen code
        if( $this->ipen_code != NULL ) {
            return $this->ipen_code;
        }
        // check if we have a parent to look for
        else if( $this->parentOrganisation != NULL) {
            return $this->parentOrganisation->getIpenCode();
        }
        // give up and return 'XX' (which is the default)
        else {
            return 'XX';
        }
    }
    
    /**
     * Helper function for translating an old IDRevier based entry to a new organisation
     * @param int $IDRevier ID of old Revier
     * @param int $FreilandNr First two digits of FreilandNr used for more specialized assignment
     * @return Organisation
     */
    public static function getFromIDRevier($IDRevier, $FreilandNr = NULL) {
        $IDRevierToOrganisationId = array(
            71 => 7,
            78 => 12,
            79 => 24,
            80 => 9,
            81 => 8,
            82 => 30,
            83 => 31,
            84 => 37,
            85 => 32,
            86 => 36,
            87 => 39,
            88 => 29,
            89 => 33,
            90 => 34,
            91 => 6,
            92 => 15,
            99 => 44,
            106 => 57,
            107 => 11,
            108 => 13,
            111 => 24,
            112 => 27,
            113 => 26,
            114 => 25,
            118 => 73,
            120 => 79,
            122 => 119,
            123 => 38,
            124 => 55,
            125 => 40,
            126 => 42,
            127 => 25,
            130 => 26,
            132 => 27,
            133 => 121,
            135 => 76,
            136 => 53,
            138 => 16,
            139 => 13,
            140 => 23,
            141 => 22,
            142 => 19,
            143 => 15,
            144 => 17,
            145 => 118,
            146 => 18,
            147 => 117,
            149 => 120,
        );
        
        // assigned of organisation id & freilandNr to suborganisationid
        $OrganisationIdFreilandNrToSubOrganisationId = array(
            37 => array(    // Alpinum
                51 => 45,
                56 => 46,
            ),
            33 => array(    // Arboretum 20-25
                20 => 47,
                21 => 48,
                22 => 49,
                23 => 50,
                24 => 51,
                25 => 52,
            ),
            40 => array(    // Betriebshof
                54 => 54,
            ),
            38 => array(    // Biologische gruppe
                52 => 55,
            ),
            30 => array(    // Experimentalgarten
                56 => 59,
                60 => 60,
            ),
            39 => array(    // Genetische Gruppe
                53 => 61,
            ),
            44 => array(    // Geographische Gruppe
                60 => 62,
            ),
            42 => array(    // Grünfläche hinter dem Institut
                58 => 63,
            ),
            35 => array(    // Hostischer Garten 26-34
                26 => 64,
                27 => 65,
                28 => 66,
                29 => 67,
                30 => 68,
                31 => 69,
                32 => 70,
                33 => 71,
                34 => 72,
            ),
            43 => array(    // Institutsvorplatz
                59 => 73,
            ),
            32 => array(    // Monocotyledonen 19
                19 => 74,
                60 => 75,
            ),
            31 => array(    // Nutzpflanzen
                48 => 77,
                60 => 78,
            ),
            36 => array(    // Österreich 35-47
                35 => 91,
                36 => 89,
                37 => 90,
                38 => 92,
                39 => 93,
                40 => 94,
                41 => 95,
                42 => 96,
                43 => 97,
                44 => 98,
                45 => 99,
                46 => 100,
                47 => 101,
            ),
            41 => array(    // Parkplatz
                57 => 79,
            ),
            29 => array(    // System 1-18
                1 => 84,
                2 => 85,
                3 => 86,
                4 => 87,
                5 => 88,
                6 => 104,
                7 => 105,
                8 => 106,
                9 => 107,
                10 => 108,
                11 => 109,
                12 => 110,
                13 => 111,
                14 => 112,
                15 => 113,
                16 => 114,
                17 => 115,
                18 => 116,
            ),
        );
        
        // check for valid entry
        if( !isset($IDRevierToOrganisationId[$IDRevier]) ) return NULL;
        
        // fetch organisation id
        $organisation_id = $IDRevierToOrganisationId[$IDRevier];
        
        // check if we have a possible refinement
        if( $FreilandNr != NULL && isset($OrganisationIdFreilandNrToSubOrganisationId[$organisation_id][$FreilandNr]) ) {
            $organisation_id = $OrganisationIdFreilandNrToSubOrganisationId[$organisation_id][$FreilandNr];
        }
        
        // return model for translated IDRevier
        return self::model()->findByPk($organisation_id);
    }
    
    /**
     * Recursive function for returning the image server for the current institution
     * @return null
     */
    public function getImageServer() {
        // check if we have an image server defined
        if( $this->imageServer != NULL ) {
            return $this->imageServer;
        }
        
        // if not check the parent one
        if( $this->parentOrganisation != NULL ) {
            return $this->parentOrganisation->getImageServer();
        }
        
        // otherwise we can't find any
        return NULL;
    }
}
