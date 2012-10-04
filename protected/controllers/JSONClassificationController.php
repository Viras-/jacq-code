<?php
/**
 * Controller class for providing classification related informations using a JSON interface
 * NOTE: functions are static so that they can be accessed by other Controllers directly 
 */
class JSONClassificationController extends Controller {
    /**
     * Return connection to herbarinput database
     * @return CDbConnection 
     */
    private static function getDbHerbarInput() {
        return Yii::app()->dbHerbarInput;
    }

    /**
     * Fetch a list of all references (which have a classification attached)
     * @param string $p_referenceType Type of references to return (citation, person, service, specimen)
     * @return array References information
     */
    public static function japiReferences( $referenceType ) {
        // check parameters
        $referenceType = trim($referenceType);
        if(empty($referenceType)) return array();
        
        $return = array();
        
        // get reference to input database
        $db = JSONClassificationController::getDbHerbarInput();
        $dbCommand = $db->createCommand();
        $dbRows = array();
        
        // make sure we have a valid parameter
        switch( $referenceType ) {
            case 'person':
                break;
            case 'service':
                break;
            case 'specimen':
                break;
            case 'citation':
                $dbRows = $dbCommand->select('l.titel AS referenceName, l.citationID AS referenceID')
                    ->from('tbl_lit l')
                    ->leftJoin('tbl_tax_synonymy ts', 'ts.source_citationID = l.citationID')
                    ->leftJoin('tbl_tax_classification tc', 'tc.tax_syn_ID = ts.tax_syn_ID')
                    ->where('ts.tax_syn_ID IS NOT NULL AND tc.classification_id IS NOT NULL')
                    ->group('ts.source_citationID')
                    ->order('referenceName')
                    ->queryAll();
                break;
            case 'periodical':
            default:
                $dbRows = $dbCommand->select('lp.periodical AS referenceName, l.periodicalID AS referenceID')
                    ->from('tbl_lit_periodicals lp')
                    ->leftJoin('tbl_lit l', 'l.periodicalID = lp.periodicalID')
                    ->leftJoin('tbl_tax_synonymy ts', 'ts.source_citationID = l.citationID')
                    ->leftJoin('tbl_tax_classification tc', 'tc.tax_syn_ID = ts.tax_syn_ID')
                    ->where('ts.tax_syn_ID IS NOT NULL AND tc.classification_id IS NOT NULL')
                    ->group('l.periodicalID')
                    ->order('referenceName')
                    ->queryAll();
                break;
        }
        
        // fetch all available references
        foreach( $dbRows as $dbRow ) {
            $return[] = array(
                'name' => $dbRow['referenceName'],
                'id' => $dbRow['referenceID']
            );
        }
        
        return $return;
    }
    
    /**
     * Get classification children of a given taxonID according to a given reference
     * NOTE: the function is static so that it can be called from other controllers as well
     * @param enum $referenceType Type of reference (citation, service, etc.)
     * @param int $referenceID ID of reference
     * @param int $taxonID ID of taxon
     * @return array structured array with classification information 
     */
    public static function japiChildren($referenceType, $referenceID, $taxonID = 0) {
        $results = array();
        
        // setup db query
        $db = JSONClassificationController::getDbHerbarInput();
        $dbCommand = $db->createCommand();
        
        switch( $referenceType ) {
            case 'periodical':
                // get all citations which belong to the given periodical
                $dbRows = $dbCommand->select('`herbar_view`.GetProtolog(l.citationID) AS referenceName, l.citationID AS referenceID')
                    ->from('tbl_lit l')
                    ->leftJoin('tbl_tax_synonymy ts', 'ts.source_citationID = l.citationID')
                    ->leftJoin('tbl_tax_classification tc', 'tc.tax_syn_ID = ts.tax_syn_ID')
                    ->where( array(
                        'AND',
                        'ts.tax_syn_ID IS NOT NULL',
                        'tc.classification_id IS NOT NULL',
                        'l.periodicalID = :periodicalID'
                    ), array(
                        ':periodicalID' => $referenceID
                    ))
                    ->group('ts.source_citationID')
                    ->order('referenceName')
                    ->queryAll();
                
                foreach( $dbRows as $dbRow ) {
                    $results[] = array(
                        "taxonID" => 0,
                        "referenceId" => $dbRow['referenceID'],
                        "referenceName" => $dbRow['referenceName'],
                        "referenceType" => "citation",
                        "hasChildren" => true
                    );
                }
                break;
            case 'citation':
            default:
                $where_cond = array('AND', 'ts.source_citationID = :source_citationID', 'ts.acc_taxon_ID IS NULL');
                $where_cond_values = array( ':source_citationID' => $referenceID );

                // basic query
                $dbCommand->select(
                                array(
                                    "`herbar_view`.GetScientificName( ts.`taxonID`, 0 ) AS `scientificName`",
                                    "tc.number",
                                    "tc.order",
                                    "ts.taxonID",
                                    "max(`has_children`.`tax_syn_ID` IS NOT NULL) AS `hasChildren`",
                                    "max(`has_synonyms`.`tax_syn_ID` IS NOT NULL) AS `hasSynonyms`",
                                    "(`has_basionym`.`basID` IS NOT NULL) AS `hasBasionym`"
                                )
                        )
                        ->from('tbl_tax_synonymy ts')
                        ->leftJoin('tbl_tax_classification tc', 'ts.tax_syn_ID = tc.tax_syn_ID')
                        ->leftJoin(
                                'tbl_tax_synonymy has_synonyms',
                                array(
                                    'AND',
                                    'has_synonyms.acc_taxon_ID = ts.taxonID',
                                    'has_synonyms.source_citationID = ts.source_citationID'
                                )
                        )
                        ->leftJoin('tbl_tax_classification has_children_clas', 'has_children_clas.parent_taxonID = ts.taxonID')
                        ->leftJoin(
                                'tbl_tax_synonymy has_children',
                                array(
                                    'AND',
                                    'has_children.tax_syn_ID = has_children_clas.tax_syn_ID',
                                    'has_children.source_citationID = ts.source_citationID'
                                )
                        )
                        ->leftJoin(
                                'tbl_tax_species has_basionym',
                                'ts.taxonID = has_basionym.taxonID'
                        )
                        ->group('ts.taxonID');

                // check if we search for children of a specific taxon
                if( $taxonID > 0 ) {
                    $where_cond[] = 'tc.parent_taxonID = :parent_taxonID';
                    $where_cond_values[':parent_taxonID'] = $taxonID;
                }
                // .. if not make sure we only return entries which have at least one child
                else {
                    $where_cond[] = 'tc.parent_taxonID IS NULL';
                    $where_cond[] = 'has_children.tax_syn_ID IS NOT NULL';
                }
                // apply where conditions and return all rows
                $dbRows = $dbCommand->where($where_cond,$where_cond_values)
                        ->order('order, scientificName')
                        ->queryAll();

                // process all results and create JSON-response from it
                foreach( $dbRows as $dbRow ) {
                    $results[] = array(
                        "taxonID" => $dbRow['taxonID'],
                        "referenceId" => $referenceID,
                        "referenceName" => $dbRow['scientificName'],
                        "referenceType" => "citation",
                        "hasChildren" => ($dbRow['hasChildren'] > 0 || $dbRow['hasSynonyms'] > 0 || $dbRow['hasBasionym']),
                        "referenceInfo" => array(
                            "number" => $dbRow['number'],
                            "order" => $dbRow['order']
                        )
                    );
                }
                break;
        }
        
        // return results
        return $results;
    }
    
    /**
     * fetch synonyms for a given taxonID, according to a given reference
     * @param string $referenceType type of reference
     * @param int $referenceID ID of reference
     * @param int $taxonID ID of taxon name
     * @return array List of synonyms including extra information
     */
    public static function japiSynonyms($referenceType, $referenceID, $taxonID) {
        $results = array();
        $basID = 0;
        $basionymResult = null;
        
        // make sure we have correct parameters
        $referenceID = intval($referenceID);
        $taxonID = intval($taxonID);
        
        // setup db query
        $db = JSONClassificationController::getDbHerbarInput();
        $dbCommand = $db->createCommand();
        
        // check if we have a basionym
        $dbRows = $dbCommand->select(
                    array(
                        "`herbar_view`.GetScientificName(`ts`.`basID`, 0) AS `scientificName`",
                        "ts.basID"
                    )
                )
                ->from('tbl_tax_species ts')
                ->where(
                        array(
                            'AND',
                            'ts.taxonID = :taxonID',
                            'ts.basID IS NOT NULL'
                        ),
                        array(':taxonID' => $taxonID)
                )
                ->queryAll();
        // check number of returned rows
        if( count($dbRows) > 0 ) {
            $basID = $dbRows[0]['basID'];
            
            $basionymResult = array(
                "taxonID" => $basID,
                "referenceName" => $dbRows[0]['scientificName'],
                "referenceId" => $referenceID,
                "referenceType" => $referenceType,
                "referenceInfo" => array(
                    "type" => "homotype",
                    "cited" => false
                )
            );
        }
        
        // new command for actual synonyms
        $dbCommand = $db->createCommand();
        switch( $referenceType ) {
            case 'citation':
                $dbRows = $dbCommand->select("`herbar_view`.GetScientificName( ts.taxonID, 0 ) AS scientificName, ts.taxonID, (tsp.basID = tsp_source.basID) AS homotype")
                    ->from("tbl_tax_synonymy ts")
                    ->leftJoin(
                            "tbl_tax_species tsp",
                            array(
                                "AND",
                                "tsp.taxonID = ts.taxonID"
                            )
                    )
                    ->leftJoin(
                            "tbl_tax_species tsp_source",
                            array(
                                "AND",
                                "tsp_source.taxonID = ts.acc_taxon_ID"
                            )
                    )
                    ->where(
                            "ts.acc_taxon_ID = :acc_taxon_ID AND source_citationID = :referenceID",
                            array( ':acc_taxon_ID' => $taxonID, ':referenceID' => $referenceID )
                    )
                    ->queryAll();
                
                foreach( $dbRows as $dbRow ) {
                    // ignore if synonym is basionym
                    if( $dbRow['taxonID'] == $basID ) {
                        $basionymResult["referenceInfo"]["cited"] = true;
                        continue;
                    }
                    
                    $results[] = array(
                        "taxonID" => $dbRow['taxonID'],
                        "referenceName" => $dbRow['scientificName'],
                        "referenceId" => $referenceID,
                        "referenceType" => $referenceType,
                        "referenceInfo" => array(
                            "type" => ($dbRow['homotype'] > 0) ? "homotype" : "heterotype",
                            'cited' => true
                        )
                    );
                }
                break;
        }
        
        // if we have a basionym, prepend it to list
        if( $basionymResult != null ) {
            array_unshift($results, $basionymResult);
        }
        
        return $results;
    }
    
    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }
}
