<?php
// require classification download component
Yii::import('application.components.dataBrowser.ClassificationDownloadComponent');

/**
 * Controller for all classification browser functions
 */
class ClassificationBrowserController extends JacqController {
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
     * @param string $hideScientificNameAuthors hide the scientific name authors in the download file
     */
    public function actionDownload($referenceType, $referenceId, $scientificNameId = 0, $hideScientificNameAuthors = null) {
        $models_taxSynonymy = array();
        $scientificNameId = intval($scientificNameId);
        $referenceId = intval($referenceId);
        $hideScientificNameAuthors = trim($hideScientificNameAuthors);
        
        // check for a valid reference id
        if( $referenceId <= 0 ) {
            return;
        }
        
        // parse the hide author names parameter
        if( $hideScientificNameAuthors == "true" ) {
            $hideScientificNameAuthors = true;
        }
        else if( $hideScientificNameAuthors == "false" ) {
            $hideScientificNameAuthors = false;
        }
        else {
            $hideScientificNameAuthors = NULL;
        }
        
        // if hide scientific name authors is null, use preference from literature entry
        if( $hideScientificNameAuthors == NULL ) {
            $model_lit = Lit::model()->findByAttributes(array(
                'citationID' => $referenceId
            ));
            // this should never happen!
            if( $model_lit == NULL ) {
                return;
            }

            // update hide-scientific name authors setting from literature entry
            $hideScientificNameAuthors = ($model_lit->hideScientificNameAuthors) ? true : false;
        }
        
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
        
        // create & configure download helper component
        $classificationDownloadComponent = new ClassificationDownloadComponent();
        $classificationDownloadComponent->setHideScientificNameAuthors($hideScientificNameAuthors);
        
        // create download object
        $objPHPExcel = $classificationDownloadComponent->createDownload($models_taxSynonymy);
        
        // prepare excel sheet for download
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        
        // send header information
        header('Content-type: text/csv');
        header('Content-disposition: attachment;filename=classification.csv');        

        // provide output
        $objWriter->save('php://output');
        exit(0);
    }
}
