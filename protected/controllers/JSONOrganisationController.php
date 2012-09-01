<?php
class JSONOrganisationController extends Controller {
    public function japiGetChildren($organisation_id) {
        return array( array(
            "data" => array(
                "title" => "JSON Node",
                "icon" => "folder",
                "attr" => array( "data-organisation-id" => 0 )
            ),
            "state" => "closed"
        ) );
    }
    
    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }
}
