<?php

/**
 * This is the model class for table "tbl_lit".
 *
 * The followings are the available columns in table 'tbl_lit':
 * @property integer $citationID
 * @property string $lit_url
 * @property integer $autorID
 * @property string $jahr
 * @property string $code
 * @property string $titel
 * @property string $suptitel
 * @property integer $editorsID
 * @property integer $periodicalID
 * @property string $vol
 * @property string $part
 * @property string $pp
 * @property string $ppSort
 * @property integer $publisherID
 * @property string $verlagsort
 * @property string $keywords
 * @property string $annotation
 * @property string $additions
 * @property string $bestand
 * @property string $signature
 * @property string $publ
 * @property string $category
 * @property integer $hideScientificNameAuthors
 * @property integer $locked
 */
class Lit extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_lit';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('autorID, editorsID, periodicalID, publisherID, hideScientificNameAuthors, locked', 'numerical', 'integerOnly' => true),
            array('lit_url, ppSort', 'length', 'max' => 255),
            array('jahr, part, bestand, signature, category', 'length', 'max' => 50),
            array('code', 'length', 'max' => 25),
            array('suptitel', 'length', 'max' => 250),
            array('vol', 'length', 'max' => 20),
            array('pp', 'length', 'max' => 150),
            array('verlagsort, keywords', 'length', 'max' => 100),
            array('publ', 'length', 'max' => 1),
            array('titel, annotation, additions', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('citationID, lit_url, autorID, jahr, code, titel, suptitel, editorsID, periodicalID, vol, part, pp, ppSort, publisherID, verlagsort, keywords, annotation, additions, bestand, signature, publ, category, hideScientificNameAuthors, locked', 'safe', 'on' => 'search'),
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
            'citationID' => 'Citation',
            'lit_url' => 'Lit Url',
            'autorID' => 'Autor',
            'jahr' => 'Jahr',
            'code' => 'Code',
            'titel' => 'Titel',
            'suptitel' => 'Suptitel',
            'editorsID' => 'Editors',
            'periodicalID' => 'Periodical',
            'vol' => 'Vol',
            'part' => 'Part',
            'pp' => 'Pp',
            'ppSort' => 'Pp Sort',
            'publisherID' => 'Publisher',
            'verlagsort' => 'Verlagsort',
            'keywords' => 'Keywords',
            'annotation' => 'Annotation',
            'additions' => 'Additions',
            'bestand' => 'Bestand',
            'signature' => 'Signature',
            'publ' => 'Publ',
            'category' => 'Category',
            'hideScientificNameAuthors' => 'Hide Scientific Name Authors',
            'locked' => 'Locked',
        );
    }
    
    /**
     * Returns a readable string for this citation
     * @todo Only execute query if not done before, in order to improve performance
     * @return string The citation as string, or null if not found
     */
    public function getCitation() {
        // query the database for the scientific name
        $dbHerbarView = Yii::app()->dbHerbarView;
        $citationRows = $dbHerbarView->createCommand("SELECT GetProtolog(:citationID) AS `citation`")->queryAll( true, array(':citationID' => $this->citationID) );
        
        if( count($citationRows) > 0 ) {
            return $citationRows[0]['citation'];
        }
        
        return null;
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

        $criteria->compare('citationID', $this->citationID);
        $criteria->compare('lit_url', $this->lit_url, true);
        $criteria->compare('autorID', $this->autorID);
        $criteria->compare('jahr', $this->jahr, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('titel', $this->titel, true);
        $criteria->compare('suptitel', $this->suptitel, true);
        $criteria->compare('editorsID', $this->editorsID);
        $criteria->compare('periodicalID', $this->periodicalID);
        $criteria->compare('vol', $this->vol, true);
        $criteria->compare('part', $this->part, true);
        $criteria->compare('pp', $this->pp, true);
        $criteria->compare('ppSort', $this->ppSort, true);
        $criteria->compare('publisherID', $this->publisherID);
        $criteria->compare('verlagsort', $this->verlagsort, true);
        $criteria->compare('keywords', $this->keywords, true);
        $criteria->compare('annotation', $this->annotation, true);
        $criteria->compare('additions', $this->additions, true);
        $criteria->compare('bestand', $this->bestand, true);
        $criteria->compare('signature', $this->signature, true);
        $criteria->compare('publ', $this->publ, true);
        $criteria->compare('category', $this->category, true);
        $criteria->compare('locked', $this->locked);

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
     * @return Lit the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
