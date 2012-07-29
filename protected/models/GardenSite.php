<?php

/**
 * This is the model class for table "tbl_garden_site".
 *
 * The followings are the available columns in table 'tbl_garden_site':
 * @property integer $id
 * @property string $description
 * @property string $department
 * @property integer $greenhouse
 * @property integer $parent_garden_site_id
 * @property integer $gardener_id
 *
 * The followings are the available model relations:
 * @property GardenSite $parentGardenSite
 * @property GardenSite[] $gardenSites
 * @property User $gardener
 * @property Livingplant[] $livingplants
 */
class GardenSite extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GardenSite the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_garden_site';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('', 'required'),
            array('id, greenhouse, parent_garden_site_id, gardener_id', 'numerical', 'integerOnly' => true),
            array('description, department', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, description, department, greenhouse, parent_garden_site_id, gardener_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentGardenSite' => array(self::BELONGS_TO, 'GardenSite', 'parent_garden_site_id'),
            'gardenSites' => array(self::HAS_MANY, 'GardenSite', 'parent_garden_site_id'),
            'gardener' => array(self::BELONGS_TO, 'User', 'gardener_id'),
            'livingplants' => array(self::HAS_MANY, 'Livingplant', 'garden_site_id'),
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
            'parent_garden_site_id' => Yii::t('jacq', 'Parent Garden Site'),
            'gardener_id' => Yii::t('jacq', 'Gardener'),
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
        $criteria->compare('parent_garden_site_id', $this->parent_garden_site_id);
        $criteria->compare('gardener_id', $this->gardener_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
}
