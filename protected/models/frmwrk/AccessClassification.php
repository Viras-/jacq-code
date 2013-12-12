<?php

/**
 * This is the model class for table "frmwrk_accessClassification".
 *
 * The followings are the available columns in table 'frmwrk_accessClassification':
 * @property integer $access_classification_id
 * @property string $AuthItem_name
 * @property integer $user_id
 * @property integer $allowDeny
 * @property integer $tax_syn_ID
 *
 * The followings are the available model relations:
 * @property AuthItem $authItemName
 * @property User $user
 * @property TblTaxSynonymy $taxSyn
 */
class AccessClassification extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'frmwrk_accessClassification';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, allowDeny, tax_syn_ID', 'numerical', 'integerOnly' => true),
            array('AuthItem_name', 'length', 'max' => 64),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('access_classification_id, AuthItem_name, user_id, allowDeny, tax_syn_ID', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authItemName' => array(self::BELONGS_TO, 'AuthItem', 'AuthItem_name'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'taxSyn' => array(self::BELONGS_TO, 'TblTaxSynonymy', 'tax_syn_ID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'access_classification_id' => 'Access Classification',
            'AuthItem_name' => 'Auth Item Name',
            'user_id' => 'User',
            'allowDeny' => 'Allow Deny',
            'tax_syn_ID' => 'Tax Syn',
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

        $criteria->compare('access_classification_id', $this->access_classification_id);
        $criteria->compare('AuthItem_name', $this->AuthItem_name, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('allowDeny', $this->allowDeny);
        $criteria->compare('tax_syn_ID', $this->tax_syn_ID);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AccessClassification the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
