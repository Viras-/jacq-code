<?php
// require phpexcel for CSV / Excel download
Yii::import('ext.phpexcel.XPHPExcel');

/**
 * Helper component for downloading a classification
 *
 * @author wkoller
 */
class ClassificationDownloadComponent extends CComponent {
    /**
     * List of prefix headers for export
     * @var array 
     */
    private static $EXPORT_HEADERS = array("reference_guid", "reference", "license", "downloaded", "modified", "scientific_name_guid", "scientific_name_id", "parent_scientific_name_id", "accepted_scientific_name_id", "taxonomic_status" );
    
    /**
     * hide scientific name authors in output file
     * @var boolean 
     */
    private $hideScientificNameAuthors = false;
    
    /**
     * PHPExcelWorkSheet which the download component operates on
     * @var PHPExcel
     */
    private $pHPExcel = null;
    
    /**
     * Current row which the data is filled in to
     * @var int 
     */
    private $rowIndex = null;
    
    /**
     * Creates the PHPExcel object which is filled with the download data
     * @param array $models_taxSynonymy List of TaxSynonymy entries which are used as a starting point
     * @return PHPExcel
     */
    public function createDownload($models_taxSynonymy) {
        // create the spreadsheet
        $this->pHPExcel = XPHPExcel::createPHPExcel();
        // initialize row index
        $this->rowIndex = 1;
        
        // fill in the static column headings
        foreach(self::$EXPORT_HEADERS as $index => $header) {
            $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, $this->rowIndex, $header);
        }
        
        // fetch all ranks, sorted by hierarchy for creating the headings of the download
        $dbCriteria = new CDbCriteria();
        $dbCriteria->order = 'rank_hierarchy ASC';
        $models_taxRank = TaxRank::model()->findAll($dbCriteria);
        foreach($models_taxRank as $model_taxRank) {
            // fill in the header information, hierarchy starts with 1, but column "A" is 0
            $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(count(self::$EXPORT_HEADERS) + $model_taxRank->rank_hierarchy - 1, $this->rowIndex, $model_taxRank->rank);
        }
        
        // cycle through top-level elements and continue exporting their children
        $this->rowIndex++;
        foreach($models_taxSynonymy as $model_taxSynonymy) {
            $this->exportClassificationToPHPExcel(array(), $model_taxSynonymy);
        }
        
        // return pHPExcel object to caller
        return $this->pHPExcel;
    }
    
    /**
     * Getters & Setters
     */
    /**
     * @return boolean
     */
    public function getHideScientificNameAuthors() {
        return $this->hideScientificNameAuthors;
    }
    /**
     * @param boolean $hideScientificNameAuthors
     */
    public function setHideScientificNameAuthors($hideScientificNameAuthors) {
        $this->hideScientificNameAuthors = $hideScientificNameAuthors;
    }
        
    /**
     * Map a given tax synonymy entry to a phpexcel object, including all children recursively
     * @param array $models_parentTaxSynonymy An array of all parent tax-synonymy entries
     * @param TaxSynonymy $model_taxSynonymy The currently active tax-synonym entry
     */
    protected function exportClassificationToPHPExcel($models_parentTaxSynonymy, $model_taxSynonymy) {
        // add basic information
        $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $this->rowIndex, Yii::app()->uuidMinter->citationUrl($model_taxSynonymy->source_citationID));
        $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $this->rowIndex, $model_taxSynonymy->sourceCitation->getCitation());
        $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $this->rowIndex, Yii::app()->params['classifications_license']);
        $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $this->rowIndex, date("Y-m-d H:i:s"));
        $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $this->rowIndex, "");
        $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $this->rowIndex, Yii::app()->uuidMinter->scientificNameUrl($model_taxSynonymy->taxonID));
        $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $this->rowIndex, $model_taxSynonymy->taxonID);
        if( $model_taxSynonymy->taxClassification != null ) {
            $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $this->rowIndex, $model_taxSynonymy->taxClassification->parent_taxonID);
        }
        $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $this->rowIndex, $model_taxSynonymy->acc_taxon_ID);
        $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $this->rowIndex, ($model_taxSynonymy->acc_taxon_ID) ? 'synonym' : 'accepted');
        
        // add parent information
        foreach( $models_parentTaxSynonymy as $model_parentTaxSynonymy ) {
            $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
                    count(self::$EXPORT_HEADERS) + $model_parentTaxSynonymy->taxSpecies->taxRank->rank_hierarchy - 1,
                    $this->rowIndex,
                    $model_parentTaxSynonymy->viewTaxon->getScientificName($this->getHideScientificNameAuthors())
            );
        }
        
        // add the currently active information
        $this->pHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
                count(self::$EXPORT_HEADERS) + $model_taxSynonymy->taxSpecies->taxRank->rank_hierarchy - 1,
                $this->rowIndex,
                $model_taxSynonymy->viewTaxon->getScientificName($this->getHideScientificNameAuthors())
        );
        $this->rowIndex++;
        
        // create criteria for searching for synonyms
        $dbSynonymCriteria = new CDbCriteria();
        $dbSynonymCriteria->addColumnCondition(array(
            'source_citationID' => $model_taxSynonymy->source_citationID,
            'acc_taxon_ID' => $model_taxSynonymy->taxonID
        ));
        
        // fetch all synonyms
        $models_taxSynonymySynonyms = TaxSynonymy::model()->findAll($dbSynonymCriteria);
        foreach($models_taxSynonymySynonyms as $model_taxSynonymySynonym) {
            $this->exportClassificationToPHPExcel($models_parentTaxSynonymy, $model_taxSynonymySynonym);
        }
        
        // create search criteria for fetching all children
        $dbCriteria = new CDbCriteria();
        $dbCriteria->with = array("taxClassification");
        $dbCriteria->addColumnCondition(array(
            'source_citationID' => $model_taxSynonymy->source_citationID,
            'parent_taxonID' => $model_taxSynonymy->taxonID,
        ));
        $dbCriteria->order = 'taxClassification.order ASC';
        
        // add current entry as parent
        $models_parentTaxSynonymy[] = $model_taxSynonymy;
        
        // fetch all children
        $models_taxSynonymyChildren = TaxSynonymy::model()->findAll($dbCriteria);
        foreach($models_taxSynonymyChildren as $model_taxSynonymyChild) {
            $this->exportClassificationToPHPExcel($models_parentTaxSynonymy, $model_taxSynonymyChild);
        }
    }
}
