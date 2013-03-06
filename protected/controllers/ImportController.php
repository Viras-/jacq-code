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
                
                // save botanical object model
                if( !$model_botanicalObject->save() ) {
                    throw new Exception('Unable to save botanicalObject');
                }
                
                // create import properties & save them
                $model_importProperties = new ImportProperties();
                $model_importProperties->botanical_object_id = $model_botanicalObject->id;
                $model_importProperties->species_name = $model_importSpecies->getScientificName();
                $model_importProperties->IDPflanze = $model_akzession->IDPflanze;
                if( !$model_importProperties->save() ) {
                    throw new Exception('Unable to save importProperties');
                }
                
                $model_akzession = new Akzession();
                
                // now create living plant model & import properties
                $model_livingPlant = new LivingPlant();
                $model_livingPlant->id = $model_botanicalObject->id;
                $model_livingPlant->ipen_number = $model_akzession->IPENNr;
                $model_livingPlant->culture_notes = $model_akzession->Kulturhinweise;
                $model_livingPlant->cultivation_date = $model_akzession->Anbaudatum;
                if( !$model_livingPlant->save() ) {
                    throw new Exception('Unable to save livingPlant');
                }
                
                // import old accession numbers
                if( $model_akzession->AkzessNr != NULL ) {
                    $this->assignAccessionNumber($model_livingPlant->id, $model_akzession->AkzessNr);
                }
                if( $model_akzession->AkzessNr_alt != NULL ) {
                    $this->assignAccessionNumber($model_livingPlant->id, $model_akzession->AkzessNr_alt);
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
    
    /**
     * Helper function for assigning an accession number
     * @param int $living_plant_id ID of living plant to assign this accession number to
     * @param string $number accession number to assign
     * @throws Exception
     */
    protected function assignAccessionNumber( $living_plant_id, $number ) {
        $model_accessionNumber = new AlternativeAccessionNumber();
        $model_accessionNumber->living_plant_id = $living_plant_id;
        $model_accessionNumber->number = $number;
        if( !$model_accessionNumber->save() ) {
            throw new Exception('Unable to save accessionNumber');
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