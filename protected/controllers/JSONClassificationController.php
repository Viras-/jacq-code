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
                $where_cond = array('AND', 'ts.source_citationID = :source_citationID');
                $where_cond_values = array( ':source_citationID' => $referenceID );

                // basic query
                $dbCommand->select( "`herbar_view`.GetScientificName( ts.taxonID, 0 ) AS scientificName, tc.number, tc.order, ts.taxonID, has_children.classification_id IS NOT NULL AS hasChildren" )
                        ->from('tbl_tax_synonymy ts')
                        ->leftJoin('tbl_tax_classification tc', 'ts.tax_syn_ID = tc.tax_syn_ID')
                        ->leftJoin('tbl_tax_classification has_children', 'has_children.parent_taxonID = ts.taxonID')
                        ->group('ts.taxonID');

                // check if we search for children of a specific taxon
                if( $taxonID > 0 ) {
                    $where_cond[] = 'tc.parent_taxonID = :parent_taxonID';
                    $where_cond_values[':parent_taxonID'] = $taxonID;
                }
                // .. if not make sure we only return entries which have at least one child
                else {
                    $where_cond[] = 'tc.parent_taxonID IS NULL';
                    $where_cond[] = 'has_children.classification_id IS NOT NULL';
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
                        "hasChildren" => ($dbRow['hasChildren'] > 0),
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
    
    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }
}
