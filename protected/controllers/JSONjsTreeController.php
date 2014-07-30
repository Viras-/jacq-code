<?php
// load JSON-service controller
require("JSONClassificationController.php");

/**
 * Controller for fetching response as required by the jsTree jQuery plugin
 *
 * @author wkoller
 */
class JSONjsTreeController extends Controller {
    /**
     * Main entry function for JSON service based classification-brower requests
     * @param type $referenceType
     * @param type $referenceId
     * @param type $taxonID
     * @param type $filterId
     * @return type
     */
    public function japiClassificationBrowser($referenceType, $referenceId, $taxonID = 0, $filterId = 0) {
        // check parameters
        $referenceId = intval($referenceId);
        $taxonID = intval($taxonID);
        $filterId = intval($filterId);
        // only execute code if we have a valid reference ID
        if( $referenceId <= 0 ) return array();

        // check if we have a filter set
        if( $filterId > 0 ) {
            return $this->classificationFiltered($referenceType, $referenceId, $filterId);
        }
        else {
            return $this->classificationChildren($referenceType, $referenceId, $taxonID);
        }
    }

    /**
     * Returns the whole classification tree filtered down to a given taxonID
     * @param type $referenceType
     * @param type $referenceId
     * @param type $taxonID
     * @return string
     */
    private function classificationFiltered($referenceType, $referenceId, $taxonID) {
        $return = array();
        // collection of references to search for the taxonID in
        $references = array(
            array('referenceType' => $referenceType, 'referenceId' => $referenceId, 'taxonID' => $taxonID)
        );
        // optional citations which we look for (only for periodicals)
        $citations = null;

        // check if we have a periodical, since then we have to fetch all citations first
        if( $referenceType == 'periodical' ) {
            $citations = $this->classificationChildren($referenceType, $referenceId);

            // convert all fetched citations to references to look for
            $references = array();
            foreach( $citations as $i => $citation ) {
                $references[$i] = array(
                    'referenceType' => $citation['data']['attr']['data-reference-type'],
                    'referenceId' => $citation['data']['attr']['data-reference-id'],
                    'taxonID' => $taxonID
                );
            }
        }

        // search children for all references
        foreach($references as $refIndex => $reference) {
            // helper variables for handling the structure
            $structure = array();
            $activeChild = null;
            $bParentFound = false;

            // virtual first parent
            $currParent = array(
                'referenceType' => $reference['referenceType'],
                'referenceId' => $reference['referenceId'],
                'taxonID' => $reference['taxonID']
            );

            // find chain of parents
            while( ($currParent = JSONClassificationController::japiGetParent($currParent['referenceType'], $currParent['referenceId'], $currParent['taxonID'])) != null ) {
                $currParentChildren = $this->classificationChildren(
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
                            $currParentChildren[$i]['children'] = $structure;
                            break;
                        }
                    }
                }
                // search for taxon we are looking for and highlight it
                else {
                    foreach( $currParentChildren as $i => $currParentChild ) {
                        if( $currParentChild['data']['attr']['data-taxon-id'] == $taxonID ) {
                            $currParentChildren[$i]['data']['title'] =
                                    '<img src="images/arrow_right.png">&nbsp;' .
                                    $currParentChildren[$i]['data']['title'];
                            break;
                        }
                    }
                }

                $structure = $currParentChildren;
                $activeChild = $currParent;

                if( $currParent['taxonID'] == 0 && $citations != null ) break;

                $bParentFound = true;
            }

            // check if we found something
            if( $bParentFound ) {
                // check if we have a periodical structure
                if( $citations != null ) {
                    $citations[$refIndex]['children'] = $structure;
                    $citations[$refIndex]['state'] = 'open';
                    $return = $citations;
                }
                // if not just return the found single structure
                else {
                    error_log(var_export($structure,true));
                    $return = $structure;
                }
            }
        }

        return $return;
    }

    /**
     * Returns the next classification-level below a given taxonID
     * @param type $referenceType
     * @param type $referenceID
     * @param type $taxonID
     * @return string
     */
    private function classificationChildren($referenceType, $referenceID, $taxonID = 0) {
        $return = array();

        $infoLink = "&nbsp;<span class='infoBox'><img src='images/information.png'></span>";

        // check for synonyms
        $synonyms = JSONClassificationController::japiSynonyms($referenceType, $referenceID, $taxonID);
        if( count($synonyms) > 0 ) {
            foreach( $synonyms as $synonym ) {
                $return[] = array(
                    "data" => array(
                        "title" => (($synonym['referenceInfo']['cited']) ? $synonym["referenceName"] : '[' . $synonym["referenceName"] . ']') . $infoLink, // uncited synonyms (i.e. basionym) are shown in brackets
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
            $entry = array(
                "data" => array(
                    "title" => $child["referenceName"] . $infoLink,
                    "attr" => array(
                        "data-taxon-id" => $child["taxonID"],
                        "data-reference-id" => $child["referenceId"],
                        "data-reference-type" => $child["referenceType"],
                    )
                ),
            );
//            if ($referenceType == 'periodical') {
//                $entry['data']['attr']['title'] = $child['nrAccTaxa'] . " accepted Taxa / " . $child['nrSynonyms'] . " Synonyms.";
//            }

            // change node icon based on various aspects
            switch($child["referenceType"]) {
                case 'citation':
                    $entry["icon"] = "images/book_open.png";

//                    if( !$child["taxonID"] ) {
//                        // append download link for non scientific name entries
//                        $entry['data']['title'] .= ' <span onclick="window.location=\'' .
//                                $this->createUrl(
//                                        "dataBrowser/classificationBrowser/download",
//                                        array(
//                                            'referenceType' => $child["referenceType"],
//                                            'referenceId' => $child["referenceId"],
//                                        )
//                                ) . '\'; return false;"><img src="images/disk.png"></span>';
//                    }
                    break;
                default:
                    break;
            }
            // if entry has a taxon id, it is a scientific name entry
            if( $child["taxonID"] ) {
                // add ACL icon if user has permission
                $aclLink = "";
                if( Yii::app()->user->checkAccess('oprtn_aclClassification') ) {
                    $aclLink = "&nbsp;<span class='acl' data-tax-syn-id='" . $child['referenceInfo']['tax_syn_ID'] . "'><img src='images/user.png'></span>";
                }

                $entry["icon"] = "images/spacer.gif";
                $entry['data']['title'] .= $aclLink;

                // check for rank display
                if( $child['referenceInfo']['rank_hierarchy'] > 15 && $child['referenceInfo']['rank_hierarchy'] < 21 ) {
                    $entry['data']['title'] = $child['referenceInfo']['rank_abbr'] . ' ' . $entry['data']['title'];
                }

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
