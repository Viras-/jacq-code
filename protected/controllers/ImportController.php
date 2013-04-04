<?php
require("AutoCompleteController.php");

class ImportController extends Controller {

    public function actionImport($start = 0) {
        // import import models
        Yii::import('application.models.import.*');
        
        // setup default dbcriteria
        $dbCriteria = new CDbCriteria();
        $dbCriteria->limit = 10;
        $dbCriteria->offset = $start;
        
        // create JSON-RPC client object for taxamatch service
        $taxmatchService = new jsonRPCClient('http://131.130.131.9/taxamatch/jsonRPC/json_rpc_taxamatchMdld.php');
        
        // load next models
        $models_akzession = Akzession::model()->findAll($dbCriteria);
        
        // cycle through each akzession and port it to new structure
        foreach($models_akzession as $model_akzession) {
            //$model_akzession = new Akzession();
            
            // begin transaction
            $transaction_import = Yii::app()->db->beginTransaction();
            try {
                // load Herkunft model
                $model_importHerkunft = Herkunft::model()->findByAttributes(array('IDPflanze' => $model_akzession->IDPflanze));
                //$model_importHerkunft = new Herkunft();
                
                // create location coordinates object
                $model_locationCoordinates = new LocationCoordinates();
                if( !$model_locationCoordinates->save() ) {
                    throw new Exception('Unable to save locationCoordinates');
                }
                
                // create acquisition date object
                $model_acquisitionDate = new AcquisitionDate();
                $model_acquisitionDate->custom = $model_akzession->Eingangsdatum;
                if(!$model_acquisitionDate->save()) {
                    throw new Exception('Unable to save acquisitionDate: ' . var_export($model_acquisitionDate->getErrors(), true));
                }
                
                // create aquisition event
                $model_aquisitionEvent = new AcquisitionEvent();
                $model_aquisitionEvent->acquisition_date_id = $model_acquisitionDate->id;
                $model_aquisitionEvent->location_coordinates_id = $model_locationCoordinates->id;
                $model_aquisitionEvent->acquisition_type_id = 1;
                $model_aquisitionEvent->annotation = $model_importHerkunft->Standort;
                
                // import the collection country / place
                // construct location string for importing
                $location_components = array();
                if($model_importHerkunft->CollLand != NULL) $location_components[] = $model_importHerkunft->CollLand;
                if($model_importHerkunft->CollOrt != NULL) $location_components[] = $model_importHerkunft->CollOrt;
                $location_string = trim(implode(', ', $location_components));
                if( !empty($location_string) ) {
                    // try to find an existing entry
                    $model_location = Location::model()->findByAttributes(array(
                        'location' => $location_string
                    ));
                    // if no location with this info is found, create a new one
                    if( $model_location == NULL ) {
                        $model_location = new Location();
                        $model_location->location = $location_string;
                        if( !$model_location->save() ) {
                            throw new Exception('Unable to save location');
                        }
                    }
                    
                    // assign location id to acquisition event
                    $model_aquisitionEvent->location_id = $model_location->id;
                }
                
                // save the acquisition event after preparing all info
                if( !$model_aquisitionEvent->save() ) {
                    throw new Exception('Unable to save aquisitionEvent');
                }
                
                // add the collecting person
                $model_collectorPerson = Person::getCollector($model_importHerkunft->Collector, $model_importHerkunft->CollNr);
                if( $model_collectorPerson != NULL ) {
                    $model_acquisitionEventPerson = new AcquisitionEventPerson();
                    $model_acquisitionEventPerson->acquisition_event_id = $model_aquisitionEvent->id;
                    $model_acquisitionEventPerson->person_id = $model_collectorPerson->id;
                    if( !$model_acquisitionEventPerson->save() ) {
                        throw new Exception('Unable to save acquisitionEventPerson: ' . var_export($model_acquisitionEventPerson->getErrors(), true));
                    }
                }
                
                // create wrapper model
                $model_botanicalObject = new BotanicalObject();
                $model_botanicalObject->acquisition_event_id = $model_aquisitionEvent->id;
                $model_botanicalObject->phenology_id = 1;
                
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
                
                // Try to find a matching entry for the revier
                $model_botanicalObject->organisation_id = 1;
                $model_importRevier = Revier::model()->findByAttributes(array('IDRevier' => $model_akzession->IDRevier));
                if( $model_importRevier != NULL ) {
                    // use either unterabteilung or revierbezeichnung for finding a fitting organisation
                    $revierName = $model_importRevier->Unterabteilung;
                    if($revierName == NULL) $revierName = $model_importRevier->Revierbezeichnung;
                    
                    // create criteria for searching the organisation data
                    $dbRevierCriteria = new CDbCriteria();
                    $dbRevierCriteria->addSearchCondition('description', $revierName);
                    $model_organisation = Organisation::model()->find($dbRevierCriteria);
                    
                    // if we find a fitting organisation, assign it to the botanical object
                    if( $model_organisation != NULL ) {
                        $model_botanicalObject->organisation_id = $model_organisation->id;
                    }
                }
                
                // save botanical object model
                if( !$model_botanicalObject->save() ) {
                    throw new Exception('Unable to save botanicalObject: ' . var_export($model_botanicalObject->getErrors(), true) );
                }
                
                // create import properties & save them
                $model_importProperties = new ImportProperties();
                $model_importProperties->botanical_object_id = $model_botanicalObject->id;
                $model_importProperties->species_name = $model_importSpecies->getScientificName();
                $model_importProperties->IDPflanze = $model_akzession->IDPflanze;
                if( $model_importRevier != NULL ) {
                    $model_importProperties->Revier = $model_importRevier->Revierbezeichnung . ', ' . $model_importRevier->Unterabteilung;
                }
                if( !$model_importProperties->save() ) {
                    throw new Exception('Unable to save importProperties');
                }
                
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
                
                // import certificates
                if( $model_akzession->CITES != 0 ) {
                    $this->assignCertificate($model_livingPlant->id, 1, $model_akzession->CITES);
                }
                if( $model_akzession->PHYTO != 0 ) {
                    $this->assignCertificate($model_livingPlant->id, 2, $model_akzession->PHYTO);
                }
                if( $model_akzession->CUSTOM != 0 ) {
                    $this->assignCertificate($model_livingPlant->id, 7, $model_akzession->CUSTOM);
                }
                
                // check if there is a separation for this akzession
                if( $model_akzession->Abgang > 0 ) {
                    $model_separation = new Separation();
                    $model_separation->botanical_object_id = $model_botanicalObject->id;
                    $model_separation->separation_type_id = 1;
                    $model_separation->date = $model_akzession->AbgangDatum;
                    $model_separation->annotation = $model_akzession->AbgangMemo;
                    if( !$model_separation->save() ) {
                        throw new Exception('Unable to save separation');
                    }
                }
                
                // finally commit the import
                $transaction_import->commit();
            }
            catch( Exception $e ) {
                echo "Error during import: " . $e->getMessage() . "\n";
                
                $transaction_import->rollback();
            }
        }
        
        // continue with import
        $this->render('index', array('start' => $start + 10));
    }

    public function actionIndex() {
        $this->actionImport();
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
    
    /**
     * Helper function for assigning a certificate to the imported object
     * @param int $living_plant_id ID of living plant to assign the certificate to
     * @param int $certificate_type_id Type of certificate
     * @param string $number Certificate number
     * @throws Exception
     */
    protected function assignCertificate( $living_plant_id, $certificate_type_id, $number ) {
        $model_certificate = new Certificate();
        $model_certificate->living_plant_id = $living_plant_id;
        $model_certificate->certificate_type_id = $certificate_type_id;
        $model_certificate->number = $number;
        if( !$model_certificate->save() ) {
            throw new Exception('Unable to save certificate');
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