<?php

/**
 * This is the model class for table "view_taxon".
 *
 * The followings are the available columns in table 'view_taxon':
 * @property integer $taxonID
 * @property integer $synID
 * @property integer $basID
 * @property integer $genID
 * @property string $annotation
 * @property integer $external
 * @property string $genus
 * @property integer $DallaTorreIDs
 * @property string $DallaTorreZusatzIDs
 * @property string $author_g
 * @property string $family
 * @property string $category
 * @property string $status
 * @property integer $statusID
 * @property string $rank
 * @property integer $tax_rankID
 * @property string $rank_abbr
 * @property string $author
 * @property integer $authorID
 * @property string $Brummit_Powell_full
 * @property string $author1
 * @property integer $authorID1
 * @property string $bpf1
 * @property string $author2
 * @property integer $authorID2
 * @property string $bpf2
 * @property string $author3
 * @property integer $authorID3
 * @property string $bpf3
 * @property string $author4
 * @property integer $authorID4
 * @property string $bpf4
 * @property string $author5
 * @property integer $authorID5
 * @property string $bpf5
 * @property string $epithet
 * @property integer $epithetID
 * @property string $epithet1
 * @property integer $epithetID1
 * @property string $epithet2
 * @property integer $epithetID2
 * @property string $epithet3
 * @property integer $epithetID3
 * @property string $epithet4
 * @property integer $epithetID4
 * @property string $epithet5
 * @property integer $epithetID5
 */
class ViewTaxon extends CActiveRecord {
    const C_FAMILY_RANK = 9;
    
    protected $scientificName = null;
    protected $scientificNameAuthor = null;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ViewTaxon the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'view_taxon';
    }

    /**
     * Fetch the scientific name for the given botanical object
     * @param boolean $bNoAuthor set to true to return the name without author
     * @return string 
     */
    public function getScientificName($bNoAuthor = false) {
        if ($this->taxonID <= 0) {
            return NULL;
        }
        
        // make sure we have the scientific name info
        $this->getScientificNameComponents();
        
        // fetch basic scientific name
        $scientificName = $this->scientificName;
        
        // check if a author is needed
        if( !$bNoAuthor ) {
            $scientificName .= ' ' . $this->scientificNameAuthor;
        }

        return $scientificName;
    }
    
    /**
     * Return the name of the author of the scientific name
     * @return string
     */
    public function getScientificNameAuthor() {
        $this->getScientificNameComponents();
        
        return $this->scientificNameAuthor;
    }
    
    /**
     * Fetch the scientific name components from the database, if required
     */
    protected function getScientificNameComponents() {
        // only query database if we did not do it before
        if( $this->scientificName != NULL && $this->scientificNameAuthor != NULL )  {
            return;
        }
        
        // query the database for the scientific name
        $dbHerbarView = Yii::app()->dbHerbarView;
        $dbHerbarView->createCommand("CALL  _buildScientificNameComponents(" . $this->taxonID . ", @scientificName, @author);")->execute();
        $scientificNameComponents = $dbHerbarView->createCommand("SELECT @scientificName AS 'ScientificName', @author AS 'Author'")->queryAll();
        
        // should actually never happen
        if( count($scientificNameComponents) <= 0 ) {
            $this->scientificName = "";
            $this->scientificNameAuthor = "";
            
            return;
        }
        
        // remember the actual components
        $this->scientificName = $scientificNameComponents[0]['ScientificName'];
        $this->scientificNameAuthor = $scientificNameComponents[0]['Author'];
    }
    
    /**
     * Check if the entry is a family or not
     * @return boolean
     */
    public function isFamily() {
        if( $this->tax_rankID == self::C_FAMILY_RANK ) return true;
        
        return false;
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('taxonID, synID, basID, genID, external, DallaTorreIDs, statusID, tax_rankID, authorID, authorID1, authorID2, authorID3, authorID4, authorID5, epithetID, epithetID1, epithetID2, epithetID3, epithetID4, epithetID5', 'numerical', 'integerOnly' => true),
            array('genus', 'length', 'max' => 100),
            array('DallaTorreZusatzIDs', 'length', 'max' => 1),
            array('author_g, rank, author, author1, author2, author3, author4, author5', 'length', 'max' => 255),
            array('family, status, epithet, epithet1, epithet2, epithet3, epithet4, epithet5', 'length', 'max' => 50),
            array('category', 'length', 'max' => 2),
            array('rank_abbr', 'length', 'max' => 10),
            array('Brummit_Powell_full, bpf1, bpf2, bpf3, bpf4, bpf5', 'length', 'max' => 250),
            array('annotation', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('taxonID, synID, basID, genID, annotation, external, genus, DallaTorreIDs, DallaTorreZusatzIDs, author_g, family, category, status, statusID, rank, tax_rankID, rank_abbr, author, authorID, Brummit_Powell_full, author1, authorID1, bpf1, author2, authorID2, bpf2, author3, authorID3, bpf3, author4, authorID4, bpf4, author5, authorID5, bpf5, epithet, epithetID, epithet1, epithetID1, epithet2, epithetID2, epithet3, epithetID3, epithet4, epithetID4, epithet5, epithetID5', 'safe', 'on' => 'search'),
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
            'taxonID' => 'Taxon',
            'synID' => 'Syn',
            'basID' => 'Bas',
            'genID' => 'Gen',
            'annotation' => 'Annotation',
            'external' => 'External',
            'genus' => 'Genus',
            'DallaTorreIDs' => 'Dalla Torre Ids',
            'DallaTorreZusatzIDs' => 'Dalla Torre Zusatz Ids',
            'author_g' => 'Author G',
            'family' => 'Family',
            'category' => 'Category',
            'status' => 'Status',
            'statusID' => 'Status',
            'rank' => 'Rank',
            'tax_rankID' => 'Tax Rank',
            'rank_abbr' => 'Rank Abbr',
            'author' => 'Author',
            'authorID' => 'Author',
            'Brummit_Powell_full' => 'Brummit Powell Full',
            'author1' => 'Author1',
            'authorID1' => 'Author Id1',
            'bpf1' => 'Bpf1',
            'author2' => 'Author2',
            'authorID2' => 'Author Id2',
            'bpf2' => 'Bpf2',
            'author3' => 'Author3',
            'authorID3' => 'Author Id3',
            'bpf3' => 'Bpf3',
            'author4' => 'Author4',
            'authorID4' => 'Author Id4',
            'bpf4' => 'Bpf4',
            'author5' => 'Author5',
            'authorID5' => 'Author Id5',
            'bpf5' => 'Bpf5',
            'epithet' => 'Epithet',
            'epithetID' => 'Epithet',
            'epithet1' => 'Epithet1',
            'epithetID1' => 'Epithet Id1',
            'epithet2' => 'Epithet2',
            'epithetID2' => 'Epithet Id2',
            'epithet3' => 'Epithet3',
            'epithetID3' => 'Epithet Id3',
            'epithet4' => 'Epithet4',
            'epithetID4' => 'Epithet Id4',
            'epithet5' => 'Epithet5',
            'epithetID5' => 'Epithet Id5',
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

        $criteria->compare('taxonID', $this->taxonID);
        $criteria->compare('synID', $this->synID);
        $criteria->compare('basID', $this->basID);
        $criteria->compare('genID', $this->genID);
        $criteria->compare('annotation', $this->annotation, true);
        $criteria->compare('external', $this->external);
        $criteria->compare('genus', $this->genus, true);
        $criteria->compare('DallaTorreIDs', $this->DallaTorreIDs);
        $criteria->compare('DallaTorreZusatzIDs', $this->DallaTorreZusatzIDs, true);
        $criteria->compare('author_g', $this->author_g, true);
        $criteria->compare('family', $this->family, true);
        $criteria->compare('category', $this->category, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('statusID', $this->statusID);
        $criteria->compare('rank', $this->rank, true);
        $criteria->compare('tax_rankID', $this->tax_rankID);
        $criteria->compare('rank_abbr', $this->rank_abbr, true);
        $criteria->compare('author', $this->author, true);
        $criteria->compare('authorID', $this->authorID);
        $criteria->compare('Brummit_Powell_full', $this->Brummit_Powell_full, true);
        $criteria->compare('author1', $this->author1, true);
        $criteria->compare('authorID1', $this->authorID1);
        $criteria->compare('bpf1', $this->bpf1, true);
        $criteria->compare('author2', $this->author2, true);
        $criteria->compare('authorID2', $this->authorID2);
        $criteria->compare('bpf2', $this->bpf2, true);
        $criteria->compare('author3', $this->author3, true);
        $criteria->compare('authorID3', $this->authorID3);
        $criteria->compare('bpf3', $this->bpf3, true);
        $criteria->compare('author4', $this->author4, true);
        $criteria->compare('authorID4', $this->authorID4);
        $criteria->compare('bpf4', $this->bpf4, true);
        $criteria->compare('author5', $this->author5, true);
        $criteria->compare('authorID5', $this->authorID5);
        $criteria->compare('bpf5', $this->bpf5, true);
        $criteria->compare('epithet', $this->epithet, true);
        $criteria->compare('epithetID', $this->epithetID);
        $criteria->compare('epithet1', $this->epithet1, true);
        $criteria->compare('epithetID1', $this->epithetID1);
        $criteria->compare('epithet2', $this->epithet2, true);
        $criteria->compare('epithetID2', $this->epithetID2);
        $criteria->compare('epithet3', $this->epithet3, true);
        $criteria->compare('epithetID3', $this->epithetID3);
        $criteria->compare('epithet4', $this->epithet4, true);
        $criteria->compare('epithetID4', $this->epithetID4);
        $criteria->compare('epithet5', $this->epithet5, true);
        $criteria->compare('epithetID5', $this->epithetID5);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Provide "primary key" for view
     * @return string
     */
    public function primaryKey() {
        return 'taxonID';
    }
}
