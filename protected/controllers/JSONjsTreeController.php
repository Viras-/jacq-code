<?php
/**
 * Controller for fetching response as required by the jsTree jQuery plugin
 *
 * @author wkoller
 */
class JSONjsTreeController extends Controller {
    public function japiClassificationBrowser($referenceType, $referenceID, $taxonID = 0) {
        $return = array();
        
        // check parameters
        $referenceID = intval($referenceID);
        $taxonID = intval($taxonID);
        // only execute code if we have a valid reference ID
        if( $referenceID <= 0 ) return array();

        // load JSON-service controller and call functions
        include("JSONClassificationController.php");
        $children = JSONClassificationController::japiChildren($referenceType, $referenceID, $taxonID);
        
        foreach( $children as $child ) {
            $entry = array(
                "data" => array(
                    "title" => $child["scientificName"],
                    "attr" => array( "data-taxon-id" => $child["taxonID"] )
                ),
            );
            
            // check if we have further children
            if( $child['hasChildren'] ) $entry['state'] = 'closed';
            
            // save entry for return
            $return[] = $entry;
        }
        
        return $return;
    }
    
    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }
}
