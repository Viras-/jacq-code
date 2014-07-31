<?php

/**
 * This is the model class for table "tbl_tax_rank".
 *
 * The followings are the available columns in table 'tbl_tax_rank':
 * @property integer $tax_rankID
 * @property string $rank
 * @property string $rank_latin
 * @property string $bot_rank_suffix
 * @property string $zoo_rank_suffix
 * @property integer $rank_hierarchy
 * @property string $rank_abbr
 */
class TaxRank extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_tax_rank';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('rank_hierarchy', 'numerical', 'integerOnly' => true),
            array('rank, rank_latin', 'length', 'max' => 255),
            array('bot_rank_suffix, zoo_rank_suffix', 'length', 'max' => 50),
            array('rank_abbr', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('tax_rankID, rank, rank_latin, bot_rank_suffix, zoo_rank_suffix, rank_hierarchy, rank_abbr', 'safe', 'on' => 'search'),
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
            'tax_rankID' => 'Tax Rank',
            'rank' => 'Rank',
            'rank_latin' => 'Rank Latin',
            'bot_rank_suffix' => 'Bot Rank Suffix',
            'zoo_rank_suffix' => 'Zoo Rank Suffix',
            'rank_hierarchy' => 'Rank Hierarchy',
            'rank_abbr' => 'Rank Abbr',
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

        $criteria->compare('tax_rankID', $this->tax_rankID);
        $criteria->compare('rank', $this->rank, true);
        $criteria->compare('rank_latin', $this->rank_latin, true);
        $criteria->compare('bot_rank_suffix', $this->bot_rank_suffix, true);
        $criteria->compare('zoo_rank_suffix', $this->zoo_rank_suffix, true);
        $criteria->compare('rank_hierarchy', $this->rank_hierarchy);
        $criteria->compare('rank_abbr', $this->rank_abbr, true);

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
     * @return TaxRank the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
