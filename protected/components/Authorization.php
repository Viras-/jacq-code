<?php
/**
 * Helper component for checking access
 */
class Authorization extends CComponent {
    /**
     * Stub function which is required for a component
     */
    public function init() {
    }
    
    /**
     * Check access to botanical object
     * @param int $botanical_object_id ID of botanical object to check
     * @param int $user_id ID of user for access checking (groups are fetched automatically)
     * @return boolean|NULL true / false or NULL if no explicit setting is given
     */
    public function botanicalObjectAccess($botanical_object_id, $user_id) {
        return $this->checkAccess($user_id, AccessBotanicalObject::model(), $botanical_object_id, "botanical_object_id");
    }
    
    /**
     * Checks for group access on botanical object level
     * @param string $group Name of group to check for
     * @param int $botanical_object_id Botanical Object to check access for
     * @return integer|string empty string if no result found, 1 on granted and 0 and denied
     */
    public function botanicalObjectAccessGroup($group, $botanical_object_id) {
        return $this->checkAccessByType(AccessBotanicalObject::model(), $botanical_object_id, "botanical_object_id", $group, "group");
    }
    
    /**
     * Check for user access on botanical object level
     * @param int $user_id ID of user to check for
     * @param int $botanical_object_id ID of botanical object to check
     * @return integer|string empty string if no result found, 1 on granted and 0 and denied
     */
    public function botanicalObjectAccessUser($user_id, $botanical_object_id) {
        return $this->checkAccessByType(AccessBotanicalObject::model(), $botanical_object_id, "botanical_object_id", $user_id, "user");
    }
    
    /**
     * Check access to botanical object
     * @param int $botanical_object_id ID of botanical object to check
     * @param int $user_id ID of user for access checking (groups are fetched automatically)
     * @return boolean|NULL true / false or NULL if no explicit setting is given
     */
    protected function checkAccess($user_id, $model, $reference_id, $reference_id_name) {
        $bAllowAccess = NULL;

        // fetch all group assignments for the given user
        $groupItems = Yii::app()->authManager->getAuthItems(2, $user_id);
        
        foreach( $groupItems as $groupName => $groupItem ) {
            $groupAccess = $this->checkAccessByType($model, $reference_id, $reference_id_name, $groupName, "group");
            
            // if no entry exists, continue
            if( $groupAccess == "" ) continue;
            
            // if access is allowed, assign & stop
            if( $groupAccess == 1 ) {
                $bAllowAccess = true;
                break;
            }
            
            // by default use group access
            $bAllowAccess = $groupAccess;
        }
        
        // check user level access
        $userAccess = $this->checkAccessByType($model, $reference_id, $reference_id_name, $user_id, "user");
        if( $userAccess != NULL ) {
            $bAllowAccess = ($userAccess == 1) ? true : false;
        }
        
        // finally return access specification
        return $bAllowAccess;
    }

    /**
     * Generic function for checking the access level
     * @param CActiveRecord $model model to use for checking
     * @param int $reference_id ID of reference to check (e.g. 1)
     * @param string $reference_id_name Name of reference to check (e.g. botanical_object_id)
     * @param int $access_id ID of access to check (e.g. 5)
     * @param string $access_type either "user" or "group"
     * @return string|int returns empty string on no value given, 0 on denied 1 on granted
     */
    protected function checkAccessByType($model, $reference_id, $reference_id_name, $access_id, $access_type = "user") {
        switch($access_type) {
            case 'group':
                $access_type_name = 'AuthItem_name';
                break;
            case 'user':
                $access_type_name = 'user_id';
            default:
                break;
        }
        
        $model_access = $model->findByAttributes(
                array(
                    $access_type_name => $access_id,
                    $reference_id_name => $reference_id
                )
        );
        
        // if no entry exists, return default value
        if( $model_access == NULL ) return "";
        
        // check value of entry and return permission for it
        if( $model_access->allowDeny == 1 ) {
            return 1;
        }
        else {
            return 0;
        }
    }    
}
