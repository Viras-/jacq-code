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
                    ->where("l.category LIKE '%classification%' AND ts.tax_syn_ID IS NOT NULL AND tc.classification_id IS NOT NULL")
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
                    ->where("l.category LIKE '%classification%' AND ts.tax_syn_ID IS NOT NULL AND tc.classification_id IS NOT NULL")
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
                                    "(`has_basionym`.`basID` IS NOT NULL) AS `hasBasionym`",
                                    "tr.rank_abbr",
                                    "tr.rank_hierarchy",
                                    "ts.tax_syn_ID",
                                )
                        )
                        ->from('tbl_tax_synonymy ts')
                        ->leftJoin('tbl_tax_species tsp', 'ts.taxonID = tsp.taxonID')
                        ->leftJoin('tbl_tax_rank tr', 'tsp.tax_rankID = tr.tax_rankID')
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
                            "order" => $dbRow['order'],
                            "rank_abbr" => $dbRow['rank_abbr'],
                            "rank_hierarchy" => $dbRow['rank_hierarchy'],
                            "tax_syn_ID" => $dbRow['tax_syn_ID'],
                        )
                    );
                }
                break;
        }

        // return results
        return $results;
    }

    /**
     * fetch synonyms (and basionym) for a given taxonID, according to a given reference
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

    /**
     * Return (other) references for this name which include them in their classification
     * @param int $taxonID ID of name to look for
     * @param int $excludeReferenceId Reference-ID to exclude (to avoid returning the "active" reference)
     * @return array List of references which do include this name
     */
    public static function japiNameReferences($taxonID, $excludeReferenceId = 0) {
        $results = array();
        $taxonID = intval($taxonID);

        // check for valid parameter
        if( $taxonID <= 0 ) return null;

        $db = JSONClassificationController::getDbHerbarInput();
        // Fetch all rows
        $dbRows = $db->createCommand()
                     ->select(
                            array('ts.source_citationID AS referenceId',
                                  '`herbar_view`.GetProtolog(`ts`.`source_citationID`) AS `referenceName`'
                                 )
                        )
                     ->from('tbl_tax_synonymy ts')
                     ->leftJoin('tbl_tax_classification tc', 'tc.tax_syn_ID = ts.tax_syn_ID')
                     ->leftJoin('tbl_tax_classification has_children', 'has_children.parent_taxonID = ts.taxonID')
                     ->leftJoin('tbl_tax_synonymy has_children_syn',
                            array(
                                'AND',
                                'has_children_syn.tax_syn_ID = has_children.tax_syn_ID',
                                'has_children_syn.source_citationID = ts.source_citationID'
                            )
                        )
                     ->leftJoin('tbl_lit l', 'l.citationID = ts.source_citationID')             // direct integration
                     ->leftJoin('tbl_lit_authors le', 'le.autorID = l.editorsID')               // of tbl_lit_...
                     ->leftJoin('tbl_lit_authors la', 'la.autorID = l.autorID')                 // for (much) faster sorting
                     ->leftJoin('tbl_lit_periodicals lp', 'lp.periodicalID = l.periodicalID')   // when using ORDER by
                     ->where(
                            array(
                                'AND',
                                'ts.source_citationID IS NOT NULL',
                                'ts.acc_taxon_ID IS NULL',
                                'ts.taxonID = :taxonID',
                                array(
                                    'OR',
                                    'tc.tax_syn_ID IS NOT NULL',     // only select entries which are part of a classification
                                    'has_children_syn.tax_syn_ID IS NOT NULL'     // only select entries which are part of a classification
                                ),
                            ),
                            array( ':taxonID' => $taxonID )
                        )
                     ->group('ts.source_citationID')
                     ->order(
                            array(
                                'la.autor',
                                'l.jahr',
                                'le.autor',
                                'l.suptitel',
                                'lp.periodical',
                                'l.vol',
                                'l.part',
                                'l.pp'
                            )
                         )
                     ->queryAll();
        foreach( $dbRows as $dbRow ) {
            // check for exclude id
            if( $dbRow['referenceId'] == $excludeReferenceId ) continue;

            // check if there any classification children of the taxonID according to this reference?
            $child = $db->createCommand()
                        ->select("ts.taxonID")
                        ->from('tbl_tax_synonymy ts')
                        ->leftJoin('tbl_tax_classification tc', 'ts.tax_syn_ID = tc.tax_syn_ID')
                        ->where(
                                array(
                                    'AND',
                                    'ts.source_citationID = :source_citationID',
                                    'ts.acc_taxon_ID IS NULL',
                                    'tc.parent_taxonID = :parent_taxonID',
                                ),
                                array(
                                    ':parent_taxonID' => $taxonID,
                                    ':source_citationID' => $dbRow['referenceId'])
                                )
                        ->queryRow();
            if ($child) {
                $hasChildren = true;
            } else {
                $child = $db->createCommand()
                            ->select("ts.taxonID")
                            ->from('tbl_tax_synonymy ts')
                            ->where(
                                    array(
                                        'AND',
                                        'ts.source_citationID = :source_citationID',
                                        'ts.acc_taxon_ID = :parent_taxonID',
                                    ),
                                    array(
                                        ':parent_taxonID' => $taxonID,
                                        ':source_citationID' => $dbRow['referenceId'])
                                    )
                            ->queryRow();
                $hasChildren = ($child) ? true : false;
            }

            $results[] = array(
                "referenceName" => $dbRow['referenceName'],
                "referenceId" => $dbRow['referenceId'],
                "referenceType" => "citation",
                "taxonID" => $taxonID,
                "hasChildren" => $hasChildren
                //"hasChildren" => JSONClassificationController::hasChildren($dbRow['referenceId'], $taxonID)
                //"hasChildren" => (count(JSONClassificationController::japiChildren("citation", $dbRow['referenceId'], $taxonID)) > 0)
            );
        }

        // Fetch all synonym rows (if any)
        $dbSyns = $db->createCommand()
                     ->select(
                            array('ts.source_citationID AS referenceId',
                                  '`herbar_view`.GetProtolog(`ts`.`source_citationID`) AS `referenceName`',
                                  'ts.acc_taxon_ID AS acceptedId'
                                 )
                        )
                     ->from('tbl_tax_synonymy ts')
                     ->leftJoin('tbl_lit l', 'l.citationID = ts.source_citationID')             // direct integration
                     ->leftJoin('tbl_lit_authors le', 'le.autorID = l.editorsID')               // of tbl_lit_...
                     ->leftJoin('tbl_lit_authors la', 'la.autorID = l.autorID')                 // for (much) faster sorting
                     ->leftJoin('tbl_lit_periodicals lp', 'lp.periodicalID = l.periodicalID')   // when using ORDER by
                     ->where(
                            array(
                                'AND',
                                'ts.source_citationID IS NOT NULL',
                                'ts.source_citationID != :excludeReference',
                                'ts.acc_taxon_ID IS NOT NULL',
                                'ts.taxonID = :taxonID',
                            ),
                            array( ':taxonID' => $taxonID, ':excludeReference' => $excludeReferenceId )
                        )
                     ->group('ts.source_citationID')
                     ->order(
                            array(
                                'la.autor',
                                'l.jahr',
                                'le.autor',
                                'l.suptitel',
                                'lp.periodical',
                                'l.vol',
                                'l.part',
                                'l.pp'
                            )
                         )
                     ->queryAll();
        foreach ($dbSyns as $dbSyn) {
            // check if the accepted taxon is part of a classification
            $accRow = $db->createCommand()
                         ->select('ts.source_citationID AS referenceId')
                         ->from('tbl_tax_synonymy ts')
                         ->leftJoin('tbl_tax_classification tc', 'tc.tax_syn_ID = ts.tax_syn_ID')
                         ->leftJoin('tbl_tax_classification has_children', 'has_children.parent_taxonID = ts.taxonID')
                         ->leftJoin('tbl_tax_synonymy has_children_syn',
                                array(
                                    'AND',
                                    'has_children_syn.tax_syn_ID = has_children.tax_syn_ID',
                                    'has_children_syn.source_citationID = ts.source_citationID'
                                )
                            )
                         ->where(
                                array(
                                    'AND',
                                    'ts.source_citationID = :checkReference',
                                    'ts.acc_taxon_ID IS NULL',
                                    'ts.taxonID = :taxonID',
                                    array(
                                        'OR',
                                        'tc.tax_syn_ID IS NOT NULL',                  // only select entries which are part of a classification
                                        'has_children_syn.tax_syn_ID IS NOT NULL'     // only select entries which are part of a classification
                                    ),
                                ),
                                array( ':taxonID' => $dbSyn['acceptedId'], ':checkReference' => $dbSyn['referenceId'] )
                            )
                         ->queryRow();
            // and add the entry only if the accepted taxon is part of a classification
            if ($accRow) {
                $results[] = array(
                    "referenceName" => '= ' . $dbSyn['referenceName'],  //  mark the reference Name as synonym
                    "referenceId" => $dbSyn['referenceId'],
                    "referenceType" => "citation",
                    "taxonID" => $taxonID,
                    "hasChildren" => false,
                );
            }
        }

        return $results;
    }

    public static function japiGetParent($referenceType, $referenceId, $taxonID) {
        $parent = null;
        $referenceId = intval($referenceId);
        $taxonID = intval($taxonID);

        // setup db query
        $db = JSONClassificationController::getDbHerbarInput();

        switch( $referenceType ) {
            case 'periodical':
                // periodical is a top level element, so no parent
                break;
            case 'citation':
            default:
                // only necessary if taxonID is not null
                if( $taxonID > 0 ) {
                    $where_cond = array(
                        'AND',
                        'ts.source_citationID = :source_citationID',
                        'ts.acc_taxon_ID IS NULL',
                        'tschild.taxonID = :child_taxonID'
                    );
                    $where_cond_values = array(
                        ':source_citationID' => $referenceId,
                        ':child_taxonID' => $taxonID
                    );

                    // basic query
                    $dbCommand = $db->createCommand();
                    $dbCommand->select(
                                    array(
                                        "`herbar_view`.GetScientificName( ts.`taxonID`, 0 ) AS `referenceName`",
                                        "tc.number",
                                        "tc.order",
                                        "ts.taxonID",
                                    )
                            )
                            ->from('tbl_tax_synonymy ts')
                            ->leftJoin('tbl_tax_classification tc', 'ts.tax_syn_ID = tc.tax_syn_ID')
                            ->leftJoin('tbl_tax_classification tcchild', 'ts.taxonID = tcchild.parent_taxonID')
                            ->leftJoin('tbl_tax_synonymy tschild', array('AND', 'tschild.source_citationID = ts.source_citationID', 'tcchild.tax_syn_ID = tschild.tax_syn_ID'));

                    // apply where conditions and return all rows
                    $dbRows = $dbCommand->where($where_cond, $where_cond_values)
                            ->queryAll();

                    // check if we found a parent
                    if( count($dbRows) > 0 ) {
                        $dbRow = $dbRows[0];
                        $parent = array(
                            "taxonID" => $dbRow['taxonID'],
                            "referenceId" => $referenceId,
                            "referenceName" => $dbRow['referenceName'],
                            "referenceType" => "citation",
                            "referenceInfo" => array(
                                "number" => $dbRow['number'],
                                "order" => $dbRow['order']
                            )
                        );
                    }
                    // if not we either have a synonym and have to search for an accepted taxon or have to return the citation entry
                    else {
                        $accTaxon = $db->createCommand()
                                       ->select(
                                                array(
                                                    "`herbar_view`.GetScientificName( taxonID, 0 ) AS referenceName",
                                                    "acc_taxon_ID",
                                                )
                                               )
                                       ->from('tbl_tax_synonymy')
                                       ->where(
                                                array(
                                                    'AND',
                                                    'taxonID = :synID',
                                                    'source_citationID = :source_citationID',
                                                    'acc_taxon_ID IS NOT NULL'
                                                ),
                                                array(
                                                    ':synID' => $taxonID,
                                                    ':source_citationID' => $referenceId
                                                )
                                              )
                                       ->queryRow();
                        // if we have found an accepted taxon for our synonym then return it
                        if ($accTaxon) {
                            $parent = array(
                                "taxonID" => $accTaxon['acc_taxon_ID'],
                                "referenceId" => $referenceId,
                                "referenceName" => $accTaxon['referenceName'],
                                "referenceType" => "citation"
                            );
                        }
                        // if not we have to return the citation entry
                        else {
                            $dbCommand = $db->createCommand();
                            $dbRows = $dbCommand->select('`herbar_view`.GetProtolog(l.citationID) AS referenceName, l.citationID AS referenceId')
                                ->from('tbl_lit l')
                                ->where( array(
                                    'AND',
                                    'l.citationID = :referenceId'
                                ), array(
                                    ':referenceId' => $referenceId
                                ))
                                ->queryAll();

                            if( count($dbRows) > 0 ) {
                                $dbRow = $dbRows[0];
                                $parent = array(
                                    "taxonID" => 0,
                                    "referenceId" => $dbRow['referenceId'],
                                    "referenceName" => $dbRow['referenceName'],
                                    "referenceType" => "citation"
                                );
                            }
                        }
                    }
                }
                // find the top-level periodical entry
                else {
                    $dbCommand = $db->createCommand();
                    $dbRows = $dbCommand->select('lp.periodical AS referenceName, l.periodicalID AS referenceId')
                        ->from('tbl_lit_periodicals lp')
                        ->leftJoin('tbl_lit l', 'l.periodicalID = lp.periodicalID')
                        ->where('l.citationID = :referenceId', array(':referenceId' => $referenceId) )
                        ->queryAll();

                    if( count($dbRows) > 0 ) {
                        $dbRow = $dbRows[0];
                        $parent = array(
                            "taxonID" => 0,
                            "referenceId" => $dbRow['referenceId'],
                            "referenceName" => $dbRow['referenceName'],
                            "referenceType" => "periodical"
                        );
                    }
                }
                break;
        }

        // return results
        return $parent;
    }

    /**
     * Materialize a classification according to a given citation ID
     * @param int $citationID
     * @throws HttpException
     */
    public static function japiMaterializeClassification( $citationID ) {
        $citationID = intval($citationID);

        if($citationID <= 0) {
            throw new HttpException("Invalid citation ID");
        }

        $db = JSONClassificationController::getDbHerbarInput();

        $dbCommand = $db->createCommand();

        // find all entries which are not parent of another entry
        $dbRows = $dbCommand->select('ts.taxonID')
                ->from('tbl_tax_synonymy AS ts')
                ->leftJoin('tbl_tax_classification tc', 'tc.parent_taxonID = ts.taxonID')
                ->leftJoin('tbl_tax_synonymy children_syn', 'children_syn.source_citationID = ts.source_citationID AND children_syn.tax_syn_ID = tc.tax_syn_ID')
                ->where(
                        array(
                            'AND',
                            'ts.source_citationID = :source_citationID',
                            'tc.classification_id IS NULL',
                            'ts.acc_taxon_ID IS NULL'
                            ),
                        array(
                            ':source_citationID' => $citationID
                            )
                )->queryAll();

        // prepare main array for tree information
        $classification_infos = array();

        // cycle through entry and find parents
        foreach( $dbRows as $dbRow ) {
            $taxonID = $dbRow['taxonID'];

            $classification_info = array($taxonID);
            $parent = JSONClassificationController::japiGetParent('citation', $citationID, $taxonID);
            while( $parent['taxonID'] > 0 ) {
                array_unshift($classification_info, $parent['taxonID']);
                $parent = JSONClassificationController::japiGetParent('citation', $citationID, $parent['taxonID']);
            }

            $classification_infos[] = $classification_info;
        }

        // now insert the classification info into the dump table
        foreach($classification_infos as $classification_info) {
            $columns = array( 'citationID' => $citationID );
            foreach( $classification_info as $taxonID ) {
                $rank_name_row = $db->createCommand()
                        ->select('herbar_view.GetScientificName(ts.taxonID, 0) AS scientificName, tr.rank')
                        ->from('tbl_tax_species ts')
                        ->leftJoin('tbl_tax_rank tr', 'tr.tax_rankID = ts.tax_rankID')
                        ->where(array(
                            'AND',
                            'ts.taxonID = :taxonID'
                        ), array(':taxonID' => $taxonID))
                        ->queryRow();

                $columns[$rank_name_row['rank']] = $rank_name_row['scientificName'];
            }

            $db->createCommand()->insert('tbl_classification_dump', $columns);
        }

        return true;
    }

    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }

    /**
     * Are there any classification children of a given taxonID according to a given reference?
     * NOTE: the function is static so that it can be called from other static functions as well
     * @param int $referenceID ID of reference
     * @param int $taxonID ID of taxon
     * @return bool classification children present?
     */
    public static function hasChildren ($referenceID, $taxonID)
    {
        // setup db query
        $db = JSONClassificationController::getDbHerbarInput();
        $dbCommand = $db->createCommand();

        $dbCommand->select("ts.taxonID")
                ->from('tbl_tax_synonymy ts')
                ->leftJoin('tbl_tax_classification tc', 'ts.tax_syn_ID = tc.tax_syn_ID')
                ->where(
                        array(
                            'AND',
                            'ts.source_citationID = :source_citationID',
                            'ts.acc_taxon_ID IS NULL',
                            'tc.parent_taxonID = :parent_taxonID',
                        ),
                        array(
                            ':parent_taxonID' => $taxonID,
                            ':source_citationID' => $referenceID)
                );
        $dbRows = $dbCommand->queryAll();

        if (count($dbRows) > 0) {
            return true;
        } else {
            $dbCommand = $db->createCommand();
            $dbCommand->select("ts.taxonID")
                    ->from('tbl_tax_synonymy ts')
                    ->where(
                            array(
                                'AND',
                                'ts.source_citationID = :source_citationID',
                                'ts.acc_taxon_ID = :parent_taxonID',
                            ),
                            array(
                                ':parent_taxonID' => $taxonID,
                                ':source_citationID' => $referenceID)
                    );
            $dbRows = $dbCommand->queryAll();

            return (count($dbRows) > 0);
        }
    }
}