<?php

/**
 * This is the model class for table "frmwrk_user".
 *
 * The followings are the available columns in table 'frmwrk_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property integer $user_type_id
 * @property integer $employment_type_id
 * @property string $title_prefix
 * @property string $firstname
 * @property string $lastname
 * @property string $title_suffix
 * @property string $birthdate
 * @property integer $organisation_id
 *
 * The followings are the available model relations:
 * @property AuthAssignment[] $authAssignments
 * @property AccessBotanicalObject[] $accessBotanicalObjects
 * @property AccessOrganisation[] $accessOrganisations
 * @property EmploymentType $employmentType
 * @property UserType $userType
 * @property TblOrganisation $organisation
 */
class User extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'frmwrk_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, password, salt, user_type_id, employment_type_id, organisation_id', 'required'),
            array('user_type_id, employment_type_id, organisation_id', 'numerical', 'integerOnly' => true),
            array('username', 'length', 'max' => 128),
            array('password, salt', 'length', 'max' => 64),
            array('title_prefix, firstname, lastname, title_suffix', 'length', 'max' => 45),
            array('birthdate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, username, password, salt, user_type_id, employment_type_id, title_prefix, firstname, lastname, title_suffix, birthdate, organisation_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authAssignments' => array(self::HAS_MANY, 'AuthAssignment', 'userid'),
            'accessBotanicalObjects' => array(self::HAS_MANY, 'AccessBotanicalObject', 'user_id'),
            'accessOrganisations' => array(self::HAS_MANY, 'AccessOrganisation', 'user_id'),
            'employmentType' => array(self::BELONGS_TO, 'EmploymentType', 'employment_type_id'),
            'userType' => array(self::BELONGS_TO, 'UserType', 'user_type_id'),
            'organisation' => array(self::BELONGS_TO, 'TblOrganisation', 'organisation_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('jacq', 'ID'),
            'username' => Yii::t('jacq', 'Username'),
            'password' => Yii::t('jacq', 'Password'),
            'salt' => Yii::t('jacq', 'Salt'),
            'user_type_id' => Yii::t('jacq', 'User Type'),
            'employment_type_id' => Yii::t('jacq', 'Employment Type'),
            'title_prefix' => Yii::t('jacq', 'Title Prefix'),
            'firstname' => Yii::t('jacq', 'Firstname'),
            'lastname' => Yii::t('jacq', 'Lastname'),
            'title_suffix' => Yii::t('jacq', 'Title Suffix'),
            'birthdate' => Yii::t('jacq', 'Birthdate'),
            'organisation_id' => Yii::t('jacq', 'Organisation'),
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

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('salt', $this->salt, true);
        $criteria->compare('user_type_id', $this->user_type_id);
        $criteria->compare('employment_type_id', $this->employment_type_id);
        $criteria->compare('title_prefix', $this->title_prefix, true);
        $criteria->compare('firstname', $this->firstname, true);
        $criteria->compare('lastname', $this->lastname, true);
        $criteria->compare('title_suffix', $this->title_suffix, true);
        $criteria->compare('birthdate', $this->birthdate, true);
        $criteria->compare('organisation_id', $this->organisation_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
