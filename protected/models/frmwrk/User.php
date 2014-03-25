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
 * @property Organisation $organisation
 */
class User extends ActiveRecord {

    /**
     * characters used for salt generating
     */
    private $saltCharset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!§$%&/()=?-_.:,;<>';

    /**
     * length of salt hash
     * @var type 
     */
    private $saltLength = 10;

    /**
     * Temporary state variable for groups
     * saved after the user has been saved
     * @var array 
     */
    protected $groups = array();

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
            array('newPassword, salt', 'length', 'max' => 64),
            array('title_prefix, firstname, lastname, title_suffix', 'length', 'max' => 45),
            array('groups', 'type', 'type' => 'array'),
            array('birthdate', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
            array('birthdate', 'default', 'setOnEmpty' => true, 'value' => null),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, username, password, salt, user_type_id, employment_type_id, title_prefix, firstname, lastname, title_suffix, birthdate, organisation_id, groups', 'safe', 'on' => 'search'),
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
            'organisation' => array(self::BELONGS_TO, 'Organisation', 'organisation_id'),
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
            'newPassword' => Yii::t('jacq', 'New Password'),
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

    /**
     * check if the given password matches the one for this user
     * @param string $password password to check (plaintext)
     * @return boolean true if valid, else false
     */
    public function checkPassword($password) {
        // generate password hash
        $password = sha1($password . sha1($this->salt));

        // check for valid password
        if ($password == $this->password)
            return true;

        return false;
    }

    /**
     * set a new password
     * @param string $password new password (plaintext)
     */
    public function setNewPassword($password) {
        // generate new salt
        $this->updateSalt();

        // update password
        $this->password = sha1($password . sha1($this->salt));
    }

    public function getNewPassword() {
        return '';
    }

    /**
     * Set groups for assignment to this user
     * @param type $groups
     */
    public function setGroups($groups) {
        $this->groups = $groups;
    }

    /**
     * Invoked after the user has been saved, the group assignments are handled here
     */
    public function onAfterSave($event) {
        parent::onAfterSave($event);

        // first of all remove all old assignments
        $groupItems = Yii::app()->authManager->getAuthItems(2);
        foreach ($groupItems as $groupName => $groupItem) {
            Yii::app()->authManager->revoke($groupName, $this->id);
        }

        // now add new ones
        foreach ($this->groups as $group) {
            Yii::app()->authManager->assign($group, $this->id);
        }
    }

    /**
     * Receive assigned groups
     * @return type
     */
    public function getGroups() {
        return Yii::app()->authManager->getAuthItems(2, $this->id);
    }

    /**
     * generate a new salt
     */
    private function updateSalt() {
        // reset current salt
        $this->salt = '';
        // prepare variables
        $count = strlen($this->saltCharset);
        $length = $this->saltLength;

        // generate the new hash
        while ($length--) {
            $this->salt .= $this->saltCharset[mt_rand(0, $count - 1)];
        }

        $this->salt = mb_convert_encoding($this->salt, "utf-8");
    }
}
