<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
    /**
     * @var int userid
     */
    public $userid;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $model_user = User::model()->findByAttributes(array('username' => $this->username));
        if( $model_user == null ) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        else {
            if( !$model_user->checkPassword($this->password) ) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            }
            else {
                $this->userid = $model_user->id;
                $this->errorCode = self::ERROR_NONE;
            }
        }
        
        return !$this->errorCode;
    }
    
    /**
     * we are using the database id as userid
     * @return int id of user
     */
    public function getId() {
        return $this->userid;
    }
}
