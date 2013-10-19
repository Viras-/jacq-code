<?php

class ClassificationBrowserController extends Controller
{

    // URL to yii-based JACQ implementation (for JSON webservices)
    protected $JACQ_URL = "https://131.130.131.9/output/jacq-yii/";
    //protected $JACQ_URL = "http://localhost/develop.jacq/code/";

    /**
     * display the base view
     */
	public function actionIndex()
	{
        // get all parameters
        $filterId      = isset($_GET['filterId'])      ? intval($_GET['filterId'])    : 0;
        $referenceType = isset($_GET['referenceType']) ? $_GET['referenceType']       : '';
        $referenceId   = isset($_GET['referenceId'])   ? intval($_GET['referenceId']) : 0;

        // initialize variables
        $data = null;

        // check if a valid request was made
        if( $referenceType == 'citation' && $referenceId > 0 ) {
            // check if we are looking for a specific name
            if( $filterId > 0 ) {
                $data = file_get_contents($this->JACQ_URL . "index.php?r=jSONjsTree/japi&action=classificationBrowser&referenceType=citation&referenceId=" . $referenceId . "&filterId=" . $filterId);
            }
            // .. if not, fetch the "normal" tree for this reference
            else {
                $data = file_get_contents($this->JACQ_URL . "index.php?r=jSONjsTree/japi&action=classificationBrowser&referenceType=citation&referenceId=" . $referenceId);
            }
        }
        
        // Load jQuery
        Yii::app()->clientScript->registerCoreScript('jquery');

        //Yii::app()->clientScript->registerScript('uniqueid', file_get_contents('protected/views/dataBrowser/classificationBrowser/index_init.js'));

        $this->render('index', array('referenceType' => $referenceType, 'referenceId' => $referenceId, 'data' => $data));
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