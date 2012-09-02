<?php

/**
 * This is the model class for table "tbl_tree_record_file".
 *
 * The followings are the available columns in table 'tbl_tree_record_file':
 * @property integer $id
 * @property string $year
 * @property string $name
 * @property string $document_number
 *
 * The followings are the available model relations:
 * @property TreeRecordFilePage[] $treeRecordFilePages
 */
class TreeRecordFile extends CActiveRecord {

    /**
     * Holds name of uploaded file
     * @var string 
     */
    public $fileName;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TreeRecordFile the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_tree_record_file';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('year', 'length', 'max' => 4),
            array('name', 'length', 'max' => 255),
            array('fileName', 'file', 'types' => 'pdf'),
            array('document_number', 'length', 'max' => 20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, year, name, document_number', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'treeRecordFilePages' => array(self::HAS_MANY, 'TreeRecordFilePage', 'tree_record_file_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'year' => Yii::t('jacq', 'Year'),
            'name' => Yii::t('jacq', 'Name'),
            'document_number' => Yii::t('jacq', 'Document Number'),
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
        $criteria->compare('year', $this->year, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('document_number', $this->document_number, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }


    /**
     * Required for automatic logging of changes
     */
    public function behaviors() {
        return array(
            "ActiveRecordLogableBehavior" => 'application.behaviors.ActiveRecordLogableBehavior'
        );
    }
}
