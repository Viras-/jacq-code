<?php

class ClassificationBrowserController extends JacqController {
    /**
     * column offset for dynamically created hierarchy structure of the download sheet
     */
    const HIERARCHY_OFFSET = 8;

    /**
     * display the base view
     */
    public function actionIndex() {
        // get all parameters
        $filterId = isset($_GET['filterId']) ? intval($_GET['filterId']) : 0;
        $referenceType = isset($_GET['referenceType']) ? $_GET['referenceType'] : '';
        $referenceId = isset($_GET['referenceId']) ? intval($_GET['referenceId']) : 0;

        // check if a valid request was made
        if ($referenceType == 'citation' && $referenceId > 0) {
            $url = Yii::app()->params['jsonJacqUrl'] . "index.php?r=jSONjsTree/japi&action=classificationBrowser&referenceType=citation&referenceId=" . $referenceId;
            // check if we are looking for a specific name
            if ($filterId > 0) {
                $data = file_get_contents($url . "&filterId=" . $filterId);
            }
            // .. if not, fetch the "normal" tree for this reference
            else {
                $data = file_get_contents($url);
            }
        }
        else {
            $data = null;
        }

        $jsonJacqUrl = (Yii::app()->params['jsonJacqUrl']) ? Yii::app()->params['jsonJacqUrl'] : Yii::app()->getBaseUrl() . '/';

        $pathToViews = 'protected/views/dataBrowser/classificationBrowser/';

        Yii::app()->clientScript->registerScript('indexJstreeFct', file_get_contents($pathToViews . 'index_jstree_fct.js'), CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScript('indexFunctions', file_get_contents($pathToViews . 'index_functions.js'), CClientScript::POS_HEAD);

        Yii::app()->clientScript->registerScript('indexInit', file_get_contents($pathToViews . 'index_document_ready.js'), CClientScript::POS_READY);

        Yii::app()->clientScript->registerScript('var1', 'var classBrowser = ' . CJavaScript::encode($this->createUrl('/dataBrowser/classificationBrowser')) . ';', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScript('var2', 'var jacq_url = ' . CJavaScript::encode($jsonJacqUrl) . ';', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScript('var3', 'var initital_data = ' . (($data) ? $data : 'null') . ';', CClientScript::POS_HEAD);

        $this->render('index', array('referenceType' => $referenceType, 'referenceId' => $referenceId));
    }
    
    /**
     * Download a given classification as CSV file for excel
     * @param string $referenceType Type of reference ('citation', etc.)
     * @param int $referenceId ID of reference
     * @param int $scientificNameId Optional id of scientific name used as top-level entry
     */
    public function actionDownload($referenceType, $referenceId, $scientificNameId = 0) {
        // require phpexcel for CSV / Excel download
        Yii::import('ext.phpexcel.XPHPExcel');
        $models_taxSynonymy = array();
        $scientificNameId = intval($scientificNameId);
        $referenceId = intval($referenceId);
        
        // check for a valid reference id
        if( $referenceId <= 0 ) return;
        
        // check if a certain scientific name id is specified & load the fitting synonymy entry
        if( $scientificNameId > 0 ) {
            $models_taxSynonymy[] = TaxSynonymy::model()->findByAttributes(array(
                'source_citationID' => $referenceId,
                'acc_taxon_ID' => NULL,
                'taxonID' => $scientificNameId,
            ));
        }
        // if not, fetch all top-level entries for this reference
        else {
            // prepare criteria for fetching top level entries
            $dbCriteria = new CDbCriteria();
            $dbCriteria->with = array('taxClassification');
            $dbCriteria->addColumnCondition(array(
                'source_citationID' => $referenceId,
                'acc_taxon_ID' => NULL,
                'classification_id' => NULL,
            ));
            
            // load all matching synonymy entries
            $models_taxSynonymy = TaxSynonymy::model()->findAll($dbCriteria);
        }
        
        // create the spreadsheet
        $objPHPExcel = XPHPExcel::createPHPExcel();
        
        // fill in the static column headings
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "reference");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "license");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "downloaded");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "modified");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "scientific_name_id");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "parent_scientific_name_id");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "accepted_scientific_name_id");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "taxonomic_status");
        
        // fetch all ranks, sorted by hierarchy for creating the headings of the download
        $dbCriteria = new CDbCriteria();
        $dbCriteria->order = 'rank_hierarchy ASC';
        $models_taxRank = TaxRank::model()->findAll($dbCriteria);
        foreach($models_taxRank as $model_taxRank) {
            // fill in the header information, hierarchy starts with 1, but column "A" is 0
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(self::HIERARCHY_OFFSET + $model_taxRank->rank_hierarchy - 1, 1, $model_taxRank->rank);
        }
        
        // cycle through top-level elements and continue exporting their children
        $rowIndex = 2;
        foreach($models_taxSynonymy as $model_taxSynonymy) {
            $this->exportClassificationToPHPExcel($objPHPExcel->getActiveSheet(), array(), $model_taxSynonymy, $rowIndex);
        }
        
        // prepare excel sheet for download
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        
        // send header information
        header('Content-type: text/csv');
        header('Content-disposition: attachment;filename=classification.csv');        

        // provide output
        $objWriter->save('php://output');
        exit(0);
    }
    
    /**
     * Map a given tax synonymy entry to a phpexcel object, including all children recursively
     * @param PHPExcel_Worksheet $pHPExcelWorksheet The worksheet to add the information to
     * @param array $models_parentTaxSynonymy An array of all parent tax-synonymy entries
     * @param TaxSynonymy $model_taxSynonymy The currently active tax-synonym entry
     * @param int $rowIndex The row-index to add the information to
     */
    protected function exportClassificationToPHPExcel($pHPExcelWorksheet, $models_parentTaxSynonymy, $model_taxSynonymy, &$rowIndex) {
        // add basic information
        $pHPExcelWorksheet->setCellValueByColumnAndRow(0, $rowIndex, $model_taxSynonymy->sourceCitation->getCitation());
        $pHPExcelWorksheet->setCellValueByColumnAndRow(1, $rowIndex, Yii::app()->params['classifications_license']);
        $pHPExcelWorksheet->setCellValueByColumnAndRow(2, $rowIndex, date("Y-m-d H:i:s"));
        $pHPExcelWorksheet->setCellValueByColumnAndRow(3, $rowIndex, "");
        $pHPExcelWorksheet->setCellValueByColumnAndRow(4, $rowIndex, $model_taxSynonymy->taxonID);
        if( $model_taxSynonymy->taxClassification != null ) {
            $pHPExcelWorksheet->setCellValueByColumnAndRow(5, $rowIndex, $model_taxSynonymy->taxClassification->parent_taxonID);
        }
        $pHPExcelWorksheet->setCellValueByColumnAndRow(6, $rowIndex, $model_taxSynonymy->acc_taxon_ID);
        $pHPExcelWorksheet->setCellValueByColumnAndRow(7, $rowIndex, ($model_taxSynonymy->acc_taxon_ID) ? 'synonym' : 'accepted');
        
        // add parent information
        foreach( $models_parentTaxSynonymy as $model_parentTaxSynonymy ) {
            $pHPExcelWorksheet->setCellValueByColumnAndRow(self::HIERARCHY_OFFSET + $model_parentTaxSynonymy->taxSpecies->taxRank->rank_hierarchy - 1, $rowIndex, $model_parentTaxSynonymy->viewTaxon->getScientificName($model_taxSynonymy->sourceCitation->hideScientificNameAuthors));
        }
        
        // add the currently active information
        $pHPExcelWorksheet->setCellValueByColumnAndRow(self::HIERARCHY_OFFSET + $model_taxSynonymy->taxSpecies->taxRank->rank_hierarchy - 1, $rowIndex, $model_taxSynonymy->viewTaxon->getScientificName($model_taxSynonymy->sourceCitation->hideScientificNameAuthors));
        $rowIndex++;
        
        // create criteria for searching for synonyms
        $dbSynonymCriteria = new CDbCriteria();
        $dbSynonymCriteria->addColumnCondition(array(
            'source_citationID' => $model_taxSynonymy->source_citationID,
            'acc_taxon_ID' => $model_taxSynonymy->taxonID
        ));
        
        // fetch all synonyms
        $models_taxSynonymySynonyms = TaxSynonymy::model()->findAll($dbSynonymCriteria);
        foreach($models_taxSynonymySynonyms as $model_taxSynonymySynonym) {
            $this->exportClassificationToPHPExcel($pHPExcelWorksheet, $models_parentTaxSynonymy, $model_taxSynonymySynonym, $rowIndex);
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
            $this->exportClassificationToPHPExcel($pHPExcelWorksheet, $models_parentTaxSynonymy, $model_taxSynonymyChild, $rowIndex);
        }
    }
}
