<?php

class DefaultController extends JacqController
{
	public function actionIndex()
	{
		$this->render('index');
	}
}