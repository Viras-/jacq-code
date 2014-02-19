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
     * Check access to organisation
     * @param int $organisation_id ID of organisation
     * @param int $user_id ID of user for access checking (groups are fetched automatically)
     * @return boolean|NULL true / false or NULL if no explicit setting is given
     */
    public function organisationAccess($organisation_id, $user_id) {
        $organisationAccess = NULL;
        $model_organisation = Organisation::model()->findByPk($organisation_id);
        
        // sanity check, should never happen
        if( $model_organisation == NULL ) return NULL;
        
        // check access for whole organisation tree until we find an entry
        while( $organisationAccess === NULL ) {
            // check access on current level
            $organisationAccess = $this->checkAccess($user_id, AccessOrganisation::model(), $model_organisation->id, "organisation_id");
            
            // load parent organisation, if none found stop searching
            $model_organisation = $model_organisation->parentOrganisation;
            if( $model_organisation == NULL ) break;
        }

        return $organisationAccess;
    }
    
    /**
     * Checks for group access on organisation level
     * @param string $group Name of group to check for
     * @param int $organisation_id ID of organisation to check
     * @return integer|string empty string if no result found, 1 on granted and 0 and denied
     */
    public function organisationAccessGroup($group, $organisation_id) {
        return $this->checkAccessByType(AccessOrganisation::model(), $organisation_id, "organisation_id", $group, "group");
    }
    
    /**
     * Check for user access on organisation level
     * @param int $user_id ID of user to check for
     * @param int $organisation_id ID of organisation to check
     * @return integer|string empty string if no result found, 1 on granted and 0 and denied
     */
    public function organisationAccessUser($user_id, $organisation_id) {
        return $this->checkAccessByType(AccessOrganisation::model(), $organisation_id, "organisation_id", $user_id, "user");
    }
    
    /**
     * Check access using the classification structure
     * @param int $taxonID TaxonID of scientific name
     * @param int $user_id ID of user to check for
     * @return boolean|null
     */
    public function classificationAccess($taxonID, $user_id) {
        $classificationAccess = NULL;
        $models_taxSynonymy = TaxSynonymy::model()->findAllByAttributes(array(
            'taxonID' => $taxonID,
        ));
        
        // cycle through all classification entries for this scientific name
        foreach( $models_taxSynonymy as $model_taxSynonymy ) {
            $currentClassificationAccess = NULL;
            
            // do checks for classification tree until we reach the top
            while( $currentClassificationAccess === NULL ) {
                // check access on current level
                $currentClassificationAccess = $this->checkAccess($user_id, AccessClassification::model(), $model_taxSynonymy->tax_syn_ID, 'tax_syn_ID');
                
                // find parent entry
                $model_taxSynonymy = $model_taxSynonymy->getParent();
                if( $model_taxSynonymy == NULL ) break;
            }
            
            // if one entry allows access, take it as granted
            if( $currentClassificationAccess === true ) {
                $classificationAccess = true;
                break;
            }
            // otherwise check if access is forbidden and remember it as current choice
            else if( $currentClassificationAccess === false ) {
                $classificationAccess = false;
            }
        }
        
        return $classificationAccess;
    }
    
    /**
     * Checks for group access on classification level
     * @param string $group Name of group to check for
     * @param int $tax_syn_ID ID of classification entry to check
     * @return integer|string empty string if no result found, 1 on granted and 0 and denied
     */
    public function classificationAccessGroup($group, $tax_syn_ID) {
        return $this->checkAccessByType(AccessClassification::model(), $tax_syn_ID, "tax_syn_ID", $group, "group");
    }
    
    /**
     * Check for user access on classification level
     * @param int $user_id ID of user to check for
     * @param int $tax_syn_ID ID of classification entry to check
     * @return integer|string empty string if no result found, 1 on granted and 0 and denied
     */
    public function classificationAccessUser($user_id, $tax_syn_ID) {
        return $this->checkAccessByType(AccessClassification::model(), $tax_syn_ID, "tax_syn_ID", $user_id, "user");
    }
    
    /**
     * Check access for a given model / reference
     * @param int $user_id ID of user to check access for
     * @param Object $model
     * @param int $reference_id
     * @param string $reference_id_name
     * @return boolean|NULL
     */
    protected function checkAccess($user_id, $model, $reference_id, $reference_id_name) {
        $bAllowAccess = NULL;

        // fetch all group assignments for the given user
        $groupItems = Yii::app()->authManager->getAuthItems(2, $user_id);

        foreach( $groupItems as $groupName => $groupItem ) {
            $groupAccess = $this->checkAccessByType($model, $reference_id, $reference_id_name, $groupName, "group");
            
            // if no entry exists, continue
            if( $groupAccess === "" ) continue;
            
            // if access is allowed, assign & stop
            if( $groupAccess === 1 ) {
                $bAllowAccess = true;
                break;
            }
            
            // by default we disallow
            $bAllowAccess = false;
        }
        
        // check user level access
        $userAccess = $this->checkAccessByType($model, $reference_id, $reference_id_name, $user_id, "user");
        if( $userAccess !== "" ) {
            $bAllowAccess = ($userAccess === 1) ? true : false;
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

    /**
     * Helper function for returning a list of all groups
     * @return array Array with CAuthItem entries for all groups
     */
    public function listGroups() {
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
}
