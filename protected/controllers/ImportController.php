<?php
require("AutoCompleteController.php");

class ImportController extends Controller {

    public function actionImport($start = 0) {
        $dbCriteria = new CDbCriteria();
        $dbCriteria->limit = 10;
        $dbCriteria->offset = $start;
        
        // create JSON-RPC client object for taxamatch service
        $taxmatchService = new jsonRPCClient('http://131.130.131.9/taxamatch/jsonRPC/json_rpc_taxamatchMdld.php');
        
        // load next models
        $models_akzession = Akzession::model()->findAll($dbCriteria);
        
        // begin transaction
        $transaction_import = Yii::app()->db->beginTransaction();
        
        // cycle through each akzession and port it to new structure
        foreach($models_akzession as $model_akzession) {
            try {
                // create wrapper model
                $model_botanicalObject = new BotanicalObject();
                
                // parse & prepare erstelldatum to be converted to unix timestamp
                $recording_date = 0;
                if( $model_akzession->Erstelldatum != NULL ) {
                    $Erstelldatum = $model_akzession->Erstelldatum;
                    $Erstelldatum = str_replace(
                            array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'),
                            array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                            $Erstelldatum);
                    $Erstelldatum = str_replace(
                            array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'),
                            array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                            $Erstelldatum);

                    $recording_date = strtotime($Erstelldatum);
                    if($recording_date == false) {
                        throw new Exception('Invalid Erstelldatum:' . $Erstelldatum);
                    }
                    
                }

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
                $model_botanicalObject->recording_date = date('Y-m-d h:i:s', $recording_date);;
                $model_botanicalObject->annotation = $model_akzession->Bemerkungen;
                $model_botanicalObject->determination_date = $model_akzession->detdat;

                // load species name
                $model_importSpecies = Species::model()->findByPk($model_akzession->IDArt);
                if( $model_importSpecies == NULL ) {
                    throw new Exception('Invalid species');
                }

                // try to find a match
                $matches = $taxmatchService->getMatchesService('vienna', $model_importSpecies->getScientificName(), array('showSyn' => false, 'NearMatch' => false));
                foreach($matches['result'] as $result) {
                    foreach($result['searchresult'] as $searchresult) {
                        foreach($searchresult['species'] as $species) {
                            // if we find a 100% match, stop processing here
                            if($species['ratio'] == 1) {
                                $model_botanicalObject->scientific_name_id = $species['taxonID'];
                                break 3;
                            }
                        }
                    }
                }
                // if no match was found, assign default name
                if( $model_botanicalObject->scientific_name_id == 0 ) {
                    $model_botanicalObject->scientific_name_id = 46996;
                }
            }
            catch( Exception $e ) {
                echo "Error during import: " . $e->getMessage() . "\n";
            }
        }
        
        $transaction_import->rollback();
        
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