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
        $bAllowAccess = NULL;

        // fetch all group assignments for the given user
        $groupItems = Yii::app()->authManager->getAuthItems(2, $user_id);
        
        foreach( $groupItems as $groupName => $groupItem ) {
            $groupAccess = $this->botanicalObjectAccessGroup($groupName, $botanical_object_id);
            
            // if no entry exists, continue
            if( $groupAccess == NULL ) continue;
            
            // if access is allowed, assign & stop
            if( $groupAccess == true ) {
                $bAllowAccess = true;
                break;
            }
            
            // by default use group access
            $bAllowAccess = $groupAccess;
        }
        
        // check user level access
        $userAccess = $this->botanicalObjectAccessUser($user_id, $botanical_object_id);
        if( $userAccess != NULL ) {
            $bAllowAccess = $userAccess;
        }
        
        // finally return access specification
        return $bAllowAccess;
    }
    
    /**
     * Checks for group access on botanical object level
     * @param string $group Name of group to check for
     * @param int $botanical_object_id Botanical Object to check access for
     * @return boolean|NULL
     */
    public function botanicalObjectAccessGroup($group, $botanical_object_id) {
        // check if group has an assignment in the access table
        $model_accessBotanicalObject = AccessBotanicalObject::model()->findByAttributes(
                array(
                    'AuthItem_name' => $group,
                    'botanical_object_id' => $botanical_object_id
                )
        );
        
        // if no entry exists, return default value
        if( $model_accessBotanicalObject == NULL ) return NULL;
        
        // check value of entry and return permission for it
        if( $model_accessBotanicalObject->allowDeny == 1 ) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
     * Check for user access on botanical object level
     * @param int $user_id ID of user to check for
     * @param int $botanical_object_id ID of botanical object to check
     * @return boolean|NULL
     */
    public function botanicalObjectAccessUser($user_id, $botanical_object_id) {
        // try to find entry for user
        $model_accessBotanicalObject = AccessBotanicalObject::model()->findByAttributes(
                array(
                    'user_id' => $user_id,
                    'botanical_object_id' => $botanical_object_id
                )
        );
        
        // if no entry exists, return default value
        if( $model_accessBotanicalObject == NULL ) return NULL;
        
        // check value of entry and return permission for it
        if( $model_accessBotanicalObject->allowDeny == 1 ) {
            return true;
        }
        else {
            return false;
        }
    }
}
