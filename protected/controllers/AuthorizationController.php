<?php
/**
 * Provides interface functions for authorization control
 */
class AuthorizationController extends Controller {
    /**
     * Action for rendering permission mask for botanical object level
     * @param int $botanical_object_id ID of botanical object to check
     */
    public function actionAjaxBotanicalObjectAccess($botanical_object_id) {
        $this->renderPartial(
            'permissionBotanicalObject',
            array(
                'groups' => $this->listGroups(),
                'users' => $this->listUsers(),
                'botanical_object_id' => $botanical_object_id,
            ),
            false,
            true
        );
    }
    
    /**
     * Called to save the botanical object access permissions
     * @param int $botanical_object_id ID of botanical object this permission is for
     */
    public function actionAjaxBotanicalObjectAccessSave($botanical_object_id) {
        // check for invalid id
        $botanical_object_id = intval($botanical_object_id);
        if( $botanical_object_id <= 0 ) return;
        
        // cylce through groups
        foreach( $_POST['Groups'] as $groupName => $allowDeny ) {
            // empty == auto, which means delete group assignment
            if( $allowDeny == "" ) {
                AccessBotanicalObject::model()->deleteAllByAttributes(array(
                    'AuthItem_name' => $groupName,
                    'botanical_object_id' => $botanical_object_id
                ));
            }
            else {
                // otherwise update / add the according entry
                $allowDeny = intval($allowDeny);
                
                // try to find old entry
                $model_accessBotanicalObject = AccessBotanicalObject::model()->findByAttributes(array(
                    'AuthItem_name' => $groupName,
                    'botanical_object_id' => $botanical_object_id
                ));
                // if not found, create a new one
                if( $model_accessBotanicalObject == NULL ) {
                    $model_accessBotanicalObject = new AccessBotanicalObject();
                    $model_accessBotanicalObject->AuthItem_name = $groupName;
                    $model_accessBotanicalObject->botanical_object_id = $botanical_object_id;
                }
                
                // update permission & save it
                $model_accessBotanicalObject->allowDeny = $allowDeny;
                $model_accessBotanicalObject->save();
            }
        }

        // cylce through groups
        foreach( $_POST['Users'] as $user_id => $allowDeny ) {
            $user_id = intval($user_id);
            
            // empty == auto, which means delete group assignment
            if( $allowDeny == "" ) {
                AccessBotanicalObject::model()->deleteAllByAttributes(array(
                    'user_id' => $user_id,
                    'botanical_object_id' => $botanical_object_id
                ));
            }
            else {
                // otherwise update / add the according entry
                $allowDeny = intval($allowDeny);
                
                // try to find old entry
                $model_accessBotanicalObject = AccessBotanicalObject::model()->findByAttributes(array(
                    'user_id' => $user_id,
                    'botanical_object_id' => $botanical_object_id
                ));
                // if not found, create a new one
                if( $model_accessBotanicalObject == NULL ) {
                    $model_accessBotanicalObject = new AccessBotanicalObject();
                    $model_accessBotanicalObject->user_id = $user_id;
                    $model_accessBotanicalObject->botanical_object_id = $botanical_object_id;
                }
                
                // update permission & save it
                $model_accessBotanicalObject->allowDeny = $allowDeny;
                $model_accessBotanicalObject->save();
            }
        }
    }
    
    /**
     * Action for rendering permission mask for organisation level
     * @param int $organisation_id ID of organisation
     */
    public function actionAjaxOrganisationAccess($organisation_id) {
        $this->renderPartial(
            'permissionOrganisation',
            array(
                'groups' => $this->listGroups(),
                'users' => $this->listUsers(),
                'organisation_id' => $organisation_id,
            ),
            false,
            true
        );
    }
    
    /**
     * Called to save the botanical object access permissions
     * @param int $botanical_object_id ID of botanical object this permission is for
     */
    public function actionAjaxOrganisationAccessSave($organisation_id) {
        // check for invalid id
        $organisation_id = intval($organisation_id);
        if( $organisation_id <= 0 ) return;
        
        // cylce through groups
        foreach( $_POST['Groups'] as $groupName => $allowDeny ) {
            // empty == auto, which means delete group assignment
            if( $allowDeny == "" ) {
                AccessOrganisation::model()->deleteAllByAttributes(array(
                    'AuthItem_name' => $groupName,
                    'organisation_id' => $organisation_id
                ));
            }
            else {
                // otherwise update / add the according entry
                $allowDeny = intval($allowDeny);
                
                // try to find old entry
                $model_accessOrganisation = AccessOrganisation::model()->findByAttributes(array(
                    'AuthItem_name' => $groupName,
                    'organisation_id' => $organisation_id
                ));
                // if not found, create a new one
                if( $model_accessOrganisation == NULL ) {
                    $model_accessOrganisation = new AccessOrganisation();
                    $model_accessOrganisation->AuthItem_name = $groupName;
                    $model_accessOrganisation->organisation_id = $organisation_id;
                }
                
                // update permission & save it
                $model_accessOrganisation->allowDeny = $allowDeny;
                $model_accessOrganisation->save();
            }
        }

        // cylce through groups
        foreach( $_POST['Users'] as $user_id => $allowDeny ) {
            $user_id = intval($user_id);
            
            // empty == auto, which means delete group assignment
            if( $allowDeny == "" ) {
                AccessOrganisation::model()->deleteAllByAttributes(array(
                    'user_id' => $user_id,
                    'organisation_id' => $organisation_id
                ));
            }
            else {
                // otherwise update / add the according entry
                $allowDeny = intval($allowDeny);
                
                // try to find old entry
                $model_accessOrganisation = AccessOrganisation::model()->findByAttributes(array(
                    'user_id' => $user_id,
                    'organisation_id' => $organisation_id
                ));
                // if not found, create a new one
                if( $model_accessOrganisation == NULL ) {
                    $model_accessOrganisation = new AccessOrganisation();
                    $model_accessOrganisation->user_id = $user_id;
                    $model_accessOrganisation->organisation_id = $organisation_id;
                }
                
                // update permission & save it
                $model_accessOrganisation->allowDeny = $allowDeny;
                $model_accessOrganisation->save();
            }
        }
    }
    
    /**
     * Helper function for returning a list of all groups
     * @return array Array with CAuthItem entries for all groups
     */
    private function listGroups() {
        $groups = array();
        
        // fetch a list of authitems (only roles)
        $roleItems = Yii::app()->authManager->getAuthItems(2);
        
        // filter auth item roles 
        foreach($roleItems as $roleItemName => $roleItem) {
            // check if auth item entry is a name
            if( strpos($roleItemName,Yii::app()->params['groupPrefix']) === 0 ) {
                // add to list but clean the group name
                $groups[str_replace(Yii::app()->params['groupPrefix'], '', $roleItemName)] = $roleItem;
            }
        }
        
        return $groups;
    }

    /**
     * Helper function for returning a list of users
     * @return array CActiveRecord array containing all user entries
     */
    private function listUsers() {
        return User::model()->findAll();
    }
}
