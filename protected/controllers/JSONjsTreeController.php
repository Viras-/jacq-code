<?php
// load JSON-service controller
require("JSONClassificationController.php");

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
        foreach( $children as $child ) {
            $infoLink = "&nbsp;<span class='infoBox'><img src='images/information.png'></a>";
            
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
                $entry['data']['title'] .= $infoLink;
                
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
    
    
    public function japiClassificationBrowserAll($referenceType, $referenceId, $taxonID) {
        $return = array();
        $activeChild = null;
        
        // virtual first parent
        $currParent = array('referenceType' => $referenceType, 'referenceId' => $referenceId, 'taxonID' => $taxonID);

        // find chain of parents
        while( ($currParent = JSONClassificationController::japiGetParent($currParent['referenceType'], $currParent['referenceId'], $currParent['taxonID'])) != null ) {
            $currParentChildren = $this->japiClassificationBrowser(
                    $currParent['referenceType'],
                    $currParent['referenceId'],
                    $currParent['taxonID']
                    );

            // find active child among all children
            if( $activeChild != null ) {
                foreach( $currParentChildren as $i => $currParentChild ) {
                    if( $currParentChild['data']['attr']['data-reference-type'] == $activeChild['referenceType'] &&
                        $currParentChild['data']['attr']['data-reference-id'] == $activeChild['referenceId'] &&
                        $currParentChild['data']['attr']['data-taxon-id'] == $activeChild['taxonID'] ) {
                        
                        $currParentChildren[$i]['state'] = 'open';
                        $currParentChildren[$i]['children'] = $return;
                        break;
                    }
                }
            }
            
            $return = $currParentChildren;
            $activeChild = $currParent;
        }
        
        return $return;
    }
    
    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }
}
