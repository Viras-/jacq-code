<?php
require("AutoCompleteController.php");

class ImportController extends Controller {

    public function actionImport($start = 0) {
        $dbCriteria = new CDbCriteria();
        $dbCriteria->limit = 100;
        $dbCriteria->offset = $start;
        
        // load next models
        $models_akzession = Akzession::model()->findAll($dbCriteria);
        
        // cycle through each akzession and port it to new structure
        foreach($models_akzession as $model_akzession) {
            $model_akzession = new Akzession();
            
            $model_botanicalObject = new BotanicalObject();
            
            // parse & prepare erstelldatum to be converted to unix timestamp
            $Erstelldatum = $model_akzession->Erstelldatum;
            $Erstelldatum = str_replace(
                    array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'),
                    array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                    $Erstelldatum);
            $Erstelldatum = str_replace(
                    array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'),
                    array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                    $Erstelldatum);
            
            $Erstelldatum = strtotime($Erstelldatum);
            if($Erstelldatum == false) {
                error_log('Unable to import ' . $model_akzession->IDPflanze);
                continue;
            }
            $Erstelldatum = date('Y-m-d h:i:s', $Erstelldatum);
            
            // lookup determined-by in person table
            if( $model_akzession->detname != NULL ) {
                $model_determinedBy = Person::model()->findByAttributes(array('name' => $model_akzession->detname));
                if( $model_determinedBy == NULL ) {
                    $model_determinedBy = new Person();
                    $model_determinedBy->name = $model_akzession->detname;
                    $model_determinedBy->save();
                }
                
                $model_botanicalObject->determined_by_id = $model_determinedBy->id;
            }
            
            // setup properties
            $model_botanicalObject->recording_date = $Erstelldatum;
            $model_botanicalObject->annotation = $model_akzession->Bemerkungen;
            $model_botanicalObject->determination_date = $model_akzession->detdat;
            
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