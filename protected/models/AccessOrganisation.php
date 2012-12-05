<?php

/**
 * This is the model class for table "frmwrk_accessOrganisation".
 *
 * The followings are the available columns in table 'frmwrk_accessOrganisation':
 * @property integer $id
 * @property string $AuthItem_name
 * @property integer $user_id
 * @property integer $allowDeny
 * @property integer $organisation_id
 */
class AccessOrganisation extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AccessOrganisation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'frmwrk_accessOrganisation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, allowDeny, organisation_id', 'numerical', 'integerOnly' => true),
            array('AuthItem_name', 'length', 'max' => 64),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, AuthItem_name, user_id, allowDeny, organisation_id', 'safe', 'on' => 'search'),
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
            'id' => 'ID',
            'AuthItem_name' => 'Auth Item Name',
            'user_id' => 'User',
            'allowDeny' => 'Allow Deny',
            'organisation_id' => 'Organisation',
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
        $criteria->compare('AuthItem_name', $this->AuthItem_name, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('allowDeny', $this->allowDeny);
        $criteria->compare('organisation_id', $this->organisation_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}