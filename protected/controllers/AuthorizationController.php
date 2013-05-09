<?php
/**
 * Provides interface functions for authorization control
 */
class AuthorizationController extends Controller {
    public function actionAjaxAccessionAccess($botanical_object_id) {
        $this->renderPartial(
            'accession',
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
