<?php

/**
 * Description of StatisticsController
 *
 * @author aragon112358
 */
class StatisticsController extends Controller
{
    /**
     * display the base view
     */
	public function actionIndex()
	{
        $jsonJacqUrl = (Yii::app()->params['jsonJacqUrl']) ? Yii::app()->params['jsonJacqUrl'] : Yii::app()->getBaseUrl() . '/';

        $pathToViews = 'protected/views/statistics/';

        Yii::app()->clientScript->registerScript('indexInit', file_get_contents($pathToViews . 'index_document_ready.js'), CClientScript::POS_READY);

        Yii::app()->clientScript->registerScript('var1', 'var jacq_url = ' . CJavaScript::encode($jsonJacqUrl) . ';', CClientScript::POS_HEAD);

        $this->render('index', array('periodStart' => date('Y') . '-01-01', 'periodEnd' => date('Y') . '-12-31'));
        //$this->render('index', array('periodStart' => '2005-01-01', 'periodEnd' => '2005-12-31'));

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
}