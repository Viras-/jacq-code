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

        // load JSON-service controller
        include("JSONClassificationController.php");
        
        // check for synonyms
        $synonyms = JSONClassificationController::japiSynonyms($referenceType, $referenceID, $taxonID);
        if( count($synonyms) > 0 ) {
            foreach( $synonyms as $synonym ) {
                $return[] = array(
                    "data" => array(
                        "title" => ($synonym['referenceInfo']['cited']) ? $synonym["referenceName"] : '[' . $synonym["referenceName"] . ']', // uncited synonyms (i.e. basionym) are shown in brackets
                        "attr" => array(
                            "data-taxon-id" => $synonym["taxonID"],
                            "data-reference-id" => $synonym["referenceId"],
                            "data-reference-type" => $synonym["referenceType"]
                        )
                    ),
                    "icon" => ($synonym['referenceInfo']['type'] == 'homotype') ? "images/identical_to.png" : "images/equal_to.png"
                );
            }
        }
        
        // find all classification children
        $children = JSONClassificationController::japiChildren($referenceType, $referenceID, $taxonID);
        if( $taxonID > 0 ) $children = array_merge($children, JSONClassificationController::japiNameReferences($taxonID, $referenceID));
        foreach( $children as $child ) {
            $entry = array(
                "data" => array(
                    "title" => $child["referenceName"],
                    "attr" => array(
                        "data-taxon-id" => $child["taxonID"],
                        "data-reference-id" => $child["referenceId"],
                        "data-reference-type" => $child["referenceType"],
                    )
                ),
            );
            
            // change node icon based on various aspects
            switch($child["referenceType"]) {
                case 'citation':
                    $entry["icon"] = "images/book_open.png";
                    break;
                default:
                    break;
            }
            // if a taxonID is set, always use no icon
            if( $child["taxonID"] ) {
                $entry["icon"] = "images/spacer.gif";
                // taxon entries do have some additional info
                if( !empty($child['referenceInfo']['number']) ) {
                    $entry['data']['title'] = '<i><b>' . $child['referenceInfo']['number'] . '</b></i>&nbsp;'. $entry['data']['title'];
                }
            }
            
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
