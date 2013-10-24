<?php

class ClassificationBrowserController extends Controller
{
    /**
     * display the base view
     */
	public function actionIndex()
	{
        // get all parameters
        $filterId      = isset($_GET['filterId'])      ? intval($_GET['filterId'])    : 0;
        $referenceType = isset($_GET['referenceType']) ? $_GET['referenceType']       : '';
        $referenceId   = isset($_GET['referenceId'])   ? intval($_GET['referenceId']) : 0;

        // check if a valid request was made
        if( $referenceType == 'citation' && $referenceId > 0 ) {
            $url = Yii::app()->params['jsonJacqUrl'] . "index.php?r=jSONjsTree/japi&action=classificationBrowser&referenceType=citation&referenceId=" . $referenceId;
            // check if we are looking for a specific name
            if( $filterId > 0 ) {
                $data = file_get_contents($url . "&filterId=" . $filterId);
            }
            // .. if not, fetch the "normal" tree for this reference
            else {
                $data = file_get_contents($url);
            }
        } else {
            $data = null;
        }

        $jsonJacqUrl = (Yii::app()->params['jsonJacqUrl']) ? Yii::app()->params['jsonJacqUrl'] : Yii::app()->getBaseUrl() . '/';

        // Load jQuery
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('jquery.ui');

        $cssCoreUrl = Yii::app()->getClientScript()->getCoreScriptUrl();
        Yii::app()->getClientScript()->registerCssFile($cssCoreUrl . '/jui/css/base/jquery-ui.css');

        $pathToViews = 'protected/views/dataBrowser/classificationBrowser/';

        Yii::app()->clientScript->registerScript('indexJstreeFct', file_get_contents($pathToViews . 'index_jstree_fct.js'), CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScript('indexFunctions', file_get_contents($pathToViews . 'index_functions.js'), CClientScript::POS_HEAD);

        Yii::app()->clientScript->registerScript('indexInit', file_get_contents($pathToViews . 'index_document_ready.js'), CClientScript::POS_READY);

        Yii::app()->clientScript->registerScript('var1', 'var classBrowser = ' . CJavaScript::encode($this->createUrl('/dataBrowser/classificationBrowser')) . ';', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScript('var2', 'var jacq_url = ' . CJavaScript::encode($jsonJacqUrl) . ';', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScript('var3', 'var initital_data = ' . (($data) ? $data : 'null') . ';', CClientScript::POS_HEAD);

        $this->render('index', array('referenceType' => $referenceType, 'referenceId' => $referenceId));
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