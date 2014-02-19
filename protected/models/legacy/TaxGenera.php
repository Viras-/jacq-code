<?php

/**
 * This is the model class for table "tbl_tax_genera".
 *
 * The followings are the available columns in table 'tbl_tax_genera':
 * @property integer $genID
 * @property integer $genID_old
 * @property string $genus
 * @property integer $authorID
 * @property integer $DallaTorreIDs
 * @property string $DallaTorreZusatzIDs
 * @property integer $genID_inc0406
 * @property string $hybrid
 * @property integer $familyID
 * @property string $remarks
 * @property integer $accepted
 * @property integer $fk_taxonID
 * @property integer $locked
 * @property integer $external
 * @property integer $externalID
 */
class TaxGenera extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_tax_genera';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('genID_old, authorID, DallaTorreIDs, genID_inc0406, familyID, accepted, fk_taxonID, locked, external, externalID', 'numerical', 'integerOnly' => true),
            array('genus', 'length', 'max' => 100),
            array('DallaTorreZusatzIDs', 'length', 'max' => 1),
            array('hybrid', 'length', 'max' => 10),
            array('remarks', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('genID, genID_old, genus, authorID, DallaTorreIDs, DallaTorreZusatzIDs, genID_inc0406, hybrid, familyID, remarks, accepted, fk_taxonID, locked, external, externalID', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'genID' => 'Gen',
            'genID_old' => 'Gen Id Old',
            'genus' => 'Genus',
            'authorID' => 'Author',
            'DallaTorreIDs' => 'Dalla Torre Ids',
            'DallaTorreZusatzIDs' => 'Dalla Torre Zusatz Ids',
            'genID_inc0406' => 'Gen Id Inc0406',
            'hybrid' => 'Hybrid',
            'familyID' => 'Family',
            'remarks' => 'Remarks',
            'accepted' => 'Accepted',
            'fk_taxonID' => 'Fk Taxon',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('genID', $this->genID);
        $criteria->compare('genID_old', $this->genID_old);
        $criteria->compare('genus', $this->genus, true);
        $criteria->compare('authorID', $this->authorID);
        $criteria->compare('DallaTorreIDs', $this->DallaTorreIDs);
        $criteria->compare('DallaTorreZusatzIDs', $this->DallaTorreZusatzIDs, true);
        $criteria->compare('genID_inc0406', $this->genID_inc0406);
        $criteria->compare('hybrid', $this->hybrid, true);
        $criteria->compare('familyID', $this->familyID);
        $criteria->compare('remarks', $this->remarks, true);
        $criteria->compare('accepted', $this->accepted);
        $criteria->compare('fk_taxonID', $this->fk_taxonID);
        $criteria->compare('locked', $this->locked);
        $criteria->compare('external', $this->external);
        $criteria->compare('externalID', $this->externalID);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @return CDbConnection the database connection used for this class
     */
    public function getDbConnection() {
        return Yii::app()->dbHerbarInput;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TaxGenera the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
