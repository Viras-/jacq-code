<?php
class JSONOrganisationController extends Controller {
    public function japiGetChildren($organisation_id) {
        $result = array();
        $model_childOrganisations = array();
        
        // find fitting organisation
        if( $organisation_id ) {
            $model_organisation = Organisation::model()->findByPk($organisation_id);
            if( $model_organisation ) {
                $model_childOrganisations = $model_organisation->organisations;
            }
        }
        else {
            $model_childOrganisations = Organisation::model()->findAll(array('order' => 'description', 'condition' => 'parent_organisation_id IS NULL'));
        }
        
        if( $model_childOrganisations && count($model_childOrganisations) > 0 ) {
            foreach( $model_childOrganisations as $model_childOrganisation ) {
                // fetch data for display from organisation
                $entry = array(
                    "data" => array(
                        "title" => $model_childOrganisation->description,
                        "attr" => array( "data-organisation-id" => $model_childOrganisation->id )
                    )
                );
                
                // check if we have more child-organisations, if yes add closed state
                if( count($model_childOrganisation->organisations) > 0 ) {
                    $entry["state"] = "closed";
                }
                
                $result[] = $entry;
            }
        }
        
        return $result;
    }
    
    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }
}
