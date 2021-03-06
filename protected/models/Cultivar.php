<?php

/**
 * This is the model class for table "tbl_cultivar".
 *
 * The followings are the available columns in table 'tbl_cultivar':
 * @property integer $cultivar_id
 * @property integer $scientific_name_id
 * @property string $cultivar
 *
 * The followings are the available model relations:
 * @property ScientificNameInformation $scientificName
 * @property LivingPlant[] $livingPlants
 */
class Cultivar extends ActiveRecord {
    /**
     * @var helper attribute for deleting a cultivar entry
     */
    public $delete = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_cultivar';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cultivar_id, scientific_name_id, cultivar', 'required'),
            array('cultivar_id, scientific_name_id', 'numerical', 'integerOnly' => true),
            array('cultivar', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('cultivar_id, scientific_name_id, cultivar', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'scientificName' => array(self::BELONGS_TO, 'ScientificNameInformation', 'scientific_name_id'),
            'livingPlants' => array(self::HAS_MANY, 'LivingPlant', 'cultivar_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'cultivar_id' => Yii::t('jacq', 'Cultivar'),
            'scientific_name_id' => Yii::t('jacq', 'Scientific Name'),
            'cultivar' => Yii::t('jacq', 'Cultivar'),
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

        $criteria->compare('cultivar_id', $this->cultivar_id);
        $criteria->compare('scientific_name_id', $this->scientific_name_id);
        $criteria->compare('cultivar', $this->cultivar, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Cultivar the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    
    /**
     * Helper function for quoting the cultivar name, takes into account legacy entries which might contain the quotes already
     * @return string
     */
    public function getCultivarQuoted() {
        if( strpos($this->cultivar, "'") === FALSE ) {
            return "'" . $this->cultivar . "'";
        }
        else {
            return $this->cultivar;
        }
    }
}
