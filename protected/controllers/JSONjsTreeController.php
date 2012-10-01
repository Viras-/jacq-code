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
        
        // check for synonyms
        $synonyms = JSONClassificationController::japiSynonyms($referenceType, $referenceID, $taxonID);
        if( count($synonyms) > 0 ) {
            foreach( $synonyms as $synonym ) {
                $return[] = array(
                    "data" => array(
                        "title" => $synonym["referenceName"],
                        "attr" => array(
                            "data-taxon-id" => $synonym["taxonID"],
                            "data-reference-id" => $synonym["referenceId"],
                            "data-reference-type" => $synonym["referenceType"]
                        )
                    ),
                    "icon" => ($synonym['type'] == 'basionym') ? "images/identical_to.png" : "images/equal_to.png"
                );
            }
        }

        return $return;
    }
    
    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }
}
