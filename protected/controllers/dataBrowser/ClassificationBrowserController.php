<?php

class ClassificationBrowserController extends Controller {

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
    
    public function actionDownload($referenceType, $referenceId, $scientificNameId = 0) {
        // require phpexcel for CSV / Excel download
        Yii::import('ext.phpexcel.XPHPExcel');
        $models_taxSynonymy = array();
        $scientificNameId = intval($scientificNameId);
        
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
        
        // fetch all ranks, sorted by hierarchy for creating the headings of the download
        $dbCriteria = new CDbCriteria();
        $dbCriteria->order = 'rank_hierarchy ASC';
        $models_taxRank = TaxRank::model()->findAll($dbCriteria);
        foreach($models_taxRank as $model_taxRank) {
            // fill in the header information, hierarchy starts with 1, but column "A" is 0
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($model_taxRank->rank_hierarchy - 1, 1, $model_taxRank->rank);
        }
        
        // cycle through top-level elements and continue exporting their children
        $rowIndex = 2;
        foreach($models_taxSynonymy as $model_taxSynonymy) {
            $this->exportClassificationToPHPExcel($objPHPExcel->getActiveSheet(), array(), $model_taxSynonymy, $rowIndex);
        }
        
        // prepare excel sheet for download
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
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
        // add parent information
        foreach( $models_parentTaxSynonymy as $model_parentTaxSynonymy ) {
            $pHPExcelWorksheet->setCellValueByColumnAndRow($model_parentTaxSynonymy->taxSpecies->taxRank->rank_hierarchy - 1, $rowIndex, $model_parentTaxSynonymy->viewTaxon->getScientificName());
        }
        
        // add the currently active information
        $pHPExcelWorksheet->setCellValueByColumnAndRow($model_taxSynonymy->taxSpecies->taxRank->rank_hierarchy - 1, $rowIndex, $model_taxSynonymy->viewTaxon->getScientificName());
        $rowIndex++;
        
        // create search criteria for fetching all children
        $dbCriteria = new CDbCriteria();
        $dbCriteria->with = array("taxClassification");
        $dbCriteria->addColumnCondition(array(
            'source_citationID' => $model_taxSynonymy->source_citationID,
            'parent_taxonID' => $model_taxSynonymy->taxonID,
        ));
        
        // add current entry as parent
        $models_parentTaxSynonymy[] = $model_taxSynonymy;
        
        // fetch all children
        $models_taxSynonymyChildren = TaxSynonymy::model()->findAll($dbCriteria);
        foreach($models_taxSynonymyChildren as $model_taxSynonymyChild) {
            $this->exportClassificationToPHPExcel($pHPExcelWorksheet, $models_parentTaxSynonymy, $model_taxSynonymyChild, $rowIndex);
        }
    }

    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}
