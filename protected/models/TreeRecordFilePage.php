<?php

/**
 * This is the model class for table "tbl_tree_record_file_page".
 *
 * The followings are the available columns in table 'tbl_tree_record_file_page':
 * @property integer $id
 * @property integer $tree_record_file_id
 * @property integer $page
 *
 * The followings are the available model relations:
 * @property TreeRecord[] $treeRecords
 * @property TreeRecordFile $treeRecordFile
 */
class TreeRecordFilePage extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TreeRecordFilePage the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_tree_record_file_page';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tree_record_file_id', 'required'),
            array('tree_record_file_id, page', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, tree_record_file_id, page', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'treeRecords' => array(self::HAS_MANY, 'TreeRecord', 'tree_record_file_page_id'),
            'treeRecordFile' => array(self::BELONGS_TO, 'TreeRecordFile', 'tree_record_file_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'tree_record_file_id' => Yii::t('jacq', 'Tree Record File'),
            'page' => Yii::t('jacq', 'Page'),
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
        $criteria->compare('tree_record_file_id', $this->tree_record_file_id);
        $criteria->compare('page', $this->page);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
}
