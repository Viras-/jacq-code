<?php
class ImportController extends Controller {

    public function actionImport($start = 0) {
        $dbCriteria = new CDbCriteria();
        $dbCriteria->limit = 100;
        $dbCriteria->offset = $start;
        
        // load next models
        $models_akzession = Akzession::model()->findAll($dbCriteria);
        
        // cycle through each akzession and port it to new structure
        foreach($models_akzession as $model_akzession) {
            
        }
        
        $this->render('import');
    }

    public function actionIndex() {
        $this->render('index');
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