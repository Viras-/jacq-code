<?php
/**
 * Helper exception class for import errors
 */
class ImportException extends Exception {
    public function __construct($message, $activeRecord) {
        $message .= ': ' . var_export($activeRecord->getErrors(), true);
        
        parent::__construct($message);
    }
}

/**
 * Command for handling import of old data
 */
class ImportCommand extends CConsoleCommand {
    /**
     * Import data from old HBV database
     * @param type $start
     * @throws ImportException
     * @throws Exception
     */
    public function actionHBV() {
        // import import models
        Yii::import('application.models.import.*');
        // import autocomplete controller
        Yii::import('application.controllers.AutoCompleteController');
        
        // setup default dbcriteria
        $dbCriteria = new CDbCriteria();
        $dbCriteria->limit = 10;
        $dbCriteria->order = 'IDPflanze ASC';
        
        // fetch number of akzession entries
        $akzessionCount = Akzession::model()->count($dbCriteria);
        
        // create JSON-RPC client object for taxamatch service
        $taxmatchService = new jsonRPCClient('http://131.130.131.9/taxamatch/jsonRPC/json_rpc_taxamatchMdld.php');
        
        // processs Akzession entries in steps of 10
        for($akzessionStart = 0; $akzessionStart < $akzessionCount; $akzessionStart += 10) {
            $dbCriteria->offset = $akzessionStart;

            // load next models
            $models_akzession = Akzession::model()->findAll($dbCriteria);

            // cycle through each akzession and port it to new structure
            foreach($models_akzession as $i => $model_akzession) {
                print("Processing Akzession " . ($akzessionStart + $i + 1) . "/" . $akzessionCount . "\n");

                //$model_akzession = new Akzession();

                // begin transaction
                $transaction_import = Yii::app()->db->beginTransaction();
                try {
                    // load Herkunft model
                    $model_importHerkunft = Herkunft::model()->findByAttributes(array('IDPflanze' => $model_akzession->IDPflanze));
                    // if no herkunft entry is found, use default properties
                    if( $model_importHerkunft == NULL ) $model_importHerkunft = new Herkunft();

                    // create location coordinates objectb
                    $model_locationCoordinates = new LocationCoordinates();
                    if( !$model_locationCoordinates->save() ) {
                        throw new ImportException('Unable to save locationCoordinates', $model_locationCoordinates);
                    }

                    // create acquisition date object
                    $model_acquisitionDate = new AcquisitionDate();
                    $model_acquisitionDate->date = $model_importHerkunft->CollDatum;
                    if(!$model_acquisitionDate->save()) {
                        throw new ImportException('Unable to save acquisitionDate', $model_acquisitionDate);
                    }

                    // create aquisition event
                    $model_aquisitionEvent = new AcquisitionEvent();
                    $model_aquisitionEvent->acquisition_date_id = $model_acquisitionDate->id;
                    $model_aquisitionEvent->location_coordinates_id = $model_locationCoordinates->id;
                    $model_aquisitionEvent->acquisition_type_id = 1;
                    $model_aquisitionEvent->annotation = $model_importHerkunft->Standort;
                    $model_aquisitionEvent->number = $model_importHerkunft->CollNr;

                    // check if annotation is not empty, because then we need to add a separator
                    /*if( $model_aquisitionEvent->annotation != NULL && $model_importHerkunft->Bezugsquelle != NULL ) $model_aquisitionEvent->annotation .= '; ';
                    $model_aquisitionEvent->annotation .= $model_importHerkunft->Bezugsquelle;*/

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
                                throw new ImportException('Unable to save location', $model_location);
                            }
                        }

                        // assign location id to acquisition event
                        $model_aquisitionEvent->location_id = $model_location->id;
                    }

                    // save the acquisition event after preparing all info
                    if( !$model_aquisitionEvent->save() ) {
                        throw new ImportException('Unable to save aquisitionEvent', $model_aquisitionEvent);
                    }

                    // add the collecting person
                    $model_collectorPerson = Person::getByName($model_importHerkunft->Collector);
                    if( $model_collectorPerson != NULL ) {
                        $model_acquisitionEventPerson = new AcquisitionEventPerson();
                        $model_acquisitionEventPerson->acquisition_event_id = $model_aquisitionEvent->id;
                        $model_acquisitionEventPerson->person_id = $model_collectorPerson->id;
                        if( !$model_acquisitionEventPerson->save() ) {
                            throw new ImportException('Unable to save acquisitionEventPerson', $model_acquisitionEventPerson);
                        }
                    }

                    // add information on acquisition source
                    if( $model_importHerkunft->Bezugsquelle != NULL ) {
                        // try to find existing entry
                        $model_acquisitionSource = AcquisitionSource::model()->findByAttributes(array(
                            'name' => $model_importHerkunft->Bezugsquelle
                        ));
                        // if not found, create a new one
                        if( $model_acquisitionSource == NULL ) {
                            $model_acquisitionSource = new AcquisitionSource();
                            $model_acquisitionSource->name = $model_importHerkunft->Bezugsquelle;
                            if( !$model_acquisitionSource->save() ) {
                                throw new ImportException('Unable to save acquisitionSource', $model_acquisitionSource);
                            }
                        }

                        // assign the acquisition source to the event
                        $model_acquisitionEventSource = new AcquisitionEventSource();
                        $model_acquisitionEventSource->acquisition_event_id = $model_aquisitionEvent->id;
                        $model_acquisitionEventSource->acquisition_source_id = $model_acquisitionSource->acquisition_source_id;
                        $model_acquisitionEventSource->source_date = $model_importHerkunft->CollDatum;
                        if( !$model_acquisitionEventSource->save() ) {
                            throw new ImportException('Unable to save acquisitionEventSource', $model_acquisitionEventSource);
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
                    // old entries do not contain the recording date, add current date for them
                    else {
                        $recording_date = time();
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
                    $model_botanicalObject->recording_date = date('Y-m-d h:i:s', $recording_date);
                    $model_botanicalObject->annotation = $model_akzession->Bemerkungen;
                    $model_botanicalObject->determination_date = $model_akzession->detdat;

                    // load species name
                    $model_importSpecies = Species::model()->findByPk($model_akzession->IDArt);
                    if( $model_importSpecies == NULL ) {
                        throw new Exception('Invalid species');
                    }

                    // load additional information for Species
                    $model_importSysDiverses = SysDiverses::model()->findByPk($model_akzession->IDArt);

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
                        $model_botanicalObject->scientific_name_id = Yii::app()->params['indetScientificNameId'];
                    }

                    // Add addition scientific name information & prepare cultivar info
                    $model_scientificNameInformation = ScientificNameInformation::model()->findByPk($model_botanicalObject->scientific_name_id);
                    $model_cultivar = NULL;
                    // create new entry if necessary
                    if( $model_scientificNameInformation == NULL ) {
                        $model_scientificNameInformation = new ScientificNameInformation();
                        $model_scientificNameInformation->scientific_name_id = $model_botanicalObject->scientific_name_id;
                        // add additional info, if available
                        if( $model_importSysDiverses != NULL ) {
                            $model_scientificNameInformation->spatial_distribution = $model_importSysDiverses->Verbreitung;
                            $model_scientificNameInformation->common_names = $model_importSysDiverses->DtName;
                            
                            // find the habitus type if set
                            if( !empty($model_importSysDiverses->Wuchsform) ) {
                                $habitus_type_id = 0;
                                switch($model_importSysDiverses->Wuchsform) {
                                    case 'Baum':
                                        $habitus_type_id = 1;
                                        break;
                                    case 'Strauch':
                                        $habitus_type_id = 2;
                                        break;
                                    case 'Kraut':
                                        $habitus_type_id = 3;
                                        break;
                                    case 'Rhizomgeophyt':
                                        $habitus_type_id = 4;
                                        break;
                                    case 'Zwiebelgeophyt':
                                        $habitus_type_id = 5;
                                        break;
                                    default:
                                        print("Unable to find HabitusType for '" . $model_importSysDiverses->Wuchsform . "' assigned to '" . $model_importSpecies->getScientificName() . "'");
                                        break;
                                }
                                $model_scientificNameInformation->habitus_type_id = $habitus_type_id;
                            }
                            
                            // check for fitting cultivar
                            $FormCult = $model_importSpecies->getCleanFormCult();
                            if( !empty($FormCult) ) {
                                $model_cultivar = Cultivar::model()->findByAttributes(array(
                                    'scientific_name_id' => $model_scientificNameInformation->scientific_name_id,
                                    'cultivar' => $FormCult,
                                ));

                                // create new cultivar entry if it does not exist yet
                                if( $model_cultivar == NULL ) {
                                    $model_cultivar = new Cultivar();
                                    $model_cultivar->scientific_name_id = $model_scientificNameInformation->scientific_name_id;
                                    $model_cultivar->cultivar = $FormCult;
                                    if( $model_cultivar->save() ) {
                                        throw new ImportException('Unable to save cultivar', $model_cultivar);
                                    }
                                }
                            }
                        }
                        // finally save the model
                        if(!$model_scientificNameInformation->save()) {
                            throw new ImportException('Unable to save scientificNameInformation', $model_scientificNameInformation);
                        }
                    }

                    // Try to find a matching entry for the revier
                    $model_organisation = Organisation::getFromIDRevier($model_akzession->IDRevier, intval(substr($model_akzession->FreilandNr,0,2)));
                    if( $model_organisation == NULL ) {
                        throw new Exception('Unable to load Organisation for Revier: ' . $model_akzession->IDRevier);
                    }
                    $model_botanicalObject->organisation_id = $model_organisation->id;
                    // check for "Abgang" and set the flag
                    if( $model_akzession->Abgang > 0 ) $model_botanicalObject->separated = 1;

                    // save botanical object model
                    if( !$model_botanicalObject->save() ) {
                        throw new ImportException('Unable to save botanicalObject', $model_botanicalObject);
                    }

                    // create import properties & save them
                    $model_importProperties = new ImportProperties();
                    $model_importProperties->botanical_object_id = $model_botanicalObject->id;
                    $model_importProperties->species_name = $model_importSpecies->getScientificName();
                    $model_importProperties->IDPflanze = $model_akzession->IDPflanze;
                    if( $model_importSysDiverses != NULL ) {
                        $model_importProperties->Verbreitung = $model_importSysDiverses->Verbreitung;
                    }
                    if( !$model_importProperties->save() ) {
                        throw new ImportException('Unable to save importProperties', $model_importProperties);
                    }

                    // create a date entry for "Eingangsdatum"
                    $model_incomingDate = new AcquisitionDate();
                    $model_incomingDate->custom = $model_akzession->Eingangsdatum;
                    if( !$model_incomingDate->save() ) {
                        throw new ImportException('Unable to save incomingDate', $model_incomingDate);
                    }

                    // now create living plant model & import properties
                    $model_livingPlant = new LivingPlant();
                    $model_livingPlant->id = $model_botanicalObject->id;
                    if( $model_akzession->IPENNr != NULL ) {
                        $model_livingPlant->ipen_number = $model_akzession->IPENNr;
                        $model_livingPlant->ipen_locked = 1;
                    }
                    else {
                        $countryCode = "XX";
                        $ipenState = $model_organisation->greenhouse;

                        // try to find the country-code through the acquisition country
                        if( $model_importHerkunft->CollLand != NULL ) {
                            $results = Yii::app()->geoNameService->search(array(
                                'name' => $model_importHerkunft->CollLand,  // search for the collection country
                                'adminCode1' => '00',                       // find only countries
                            ));

                            // if we have a valid result, use the first countryCode found
                            if( $results['totalResultsCount'] > 0 ) {
                                $countryCode = $results['geonames'][0]['countryCode'];
                            }
                        }

                        // generate new IPEN numbers
                        $model_livingPlant->ipen_number = $countryCode . '-' . $ipenState . '-' . 'WU';
                    }
                    $model_livingPlant->culture_notes = $model_akzession->Kulturhinweise;
                    $model_livingPlant->cultivation_date = $model_akzession->Anbaudatum;
                    $model_livingPlant->incoming_date_id = $model_incomingDate->id;
                    // use old "FreilandNr" as place_number
                    $model_livingPlant->place_number = $model_akzession->FreilandNr;
                    // assign cultivar if set
                    if( $model_cultivar != NULL ) $model_livingPlant->cultivar_id = $model_cultivar->cultivar_id;

                    // save the living plant
                    if( !$model_livingPlant->save() ) {
                        throw new ImportException('Unable to save livingPlant', $model_livingPlant);
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
                            throw new ImportException('Unable to save separation', $model_separation);
                        }
                    }

                    // finally commit the import
                    $transaction_import->commit();
                }
                catch( Exception $e ) {
                    // discard any saved changes
                    $transaction_import->rollback();

                    // Save error to database
                    $model_importError = new ImportError();
                    $model_importError->IDPflanze = $model_akzession->IDPflanze;
                    $model_importError->message = $e->getMessage();
                    // last chance: log to PHP error log
                    if( !$model_importError->save() ) {
                        error_log("Can't save import error: " . implode(',', $model_importError->getErrors()));
                    }

                    // output error message
                    print("Error during import: " . $e->getMessage() . "\n");
                }
            }
        }

        print("Done!\n");
    }
    
    /**
     * Import the legacy classification from the native JACQ system into the new classification structure
     * @throws Exception
     */
    public function actionJacqLegacyClassification() {
        print("Deleting old classification entries\n");
        
        // remove all previous entries from classification
        $db = Yii::app()->dbHerbarInput;
        $db->createCommand(
                  "DELETE s, c FROM `tbl_tax_synonymy` AS s LEFT JOIN `tbl_tax_classification` c ON c.`tax_syn_ID` = s.`tax_syn_ID` WHERE s.`source_citationID` = '" . Yii::app()->params['jacqClassificationCitationId'] . "'")
                ->execute();

        /*
         * re-insert the new classification
         */
        // load all families
        $models_taxFamilies = TaxFamilies::model()->findAll();
        foreach($models_taxFamilies as $i => $model_taxFamilies) {
            print("Processing family " . ($i+1) . "/" . count($models_taxFamilies) . "\n");
            
            // find genera entry for family
            $model_taxFamiliesGenera = TaxGenera::model()->findByAttributes(array(
                'genus' => $model_taxFamilies->family
            ));
            if( $model_taxFamiliesGenera == NULL ) {
                //throw new Exception('Unable to find genus entry for family: ' . $model_taxFamilies->family);
                print("Unable to find genus entry for family: '" . $model_taxFamilies->family . "'\n");
                continue;
            }
            
            // find the species entry for the genera entry of the family
            $model_taxFamiliesSpecies = TaxSpecies::model()->findByAttributes(array(
                'genID' => $model_taxFamiliesGenera->genID,
                'tax_rankID' => 9
            ));
            if( $model_taxFamiliesSpecies == NULL ) {
                //throw new Exception('Unable to find species entry for family: ' . $model_taxFamilies->family);
                print("Unable to find species entry for family: '" . $model_taxFamilies->family . "'\n");
                continue;
            }
            
            // add the found species entry as top level element
            $model_taxSynonymyFamily = new TaxSynonymy();
            $model_taxSynonymyFamily->taxonID = $model_taxFamiliesSpecies->taxonID;
            $model_taxSynonymyFamily->userID = 0;
            $model_taxSynonymyFamily->timestamp = new CDbExpression('NOW()');
            $model_taxSynonymyFamily->source_citationID = Yii::app()->params['jacqClassificationCitationId'];
            $model_taxSynonymyFamily->source = 'literature';
            if( !$model_taxSynonymyFamily->save() ) {
                throw new Exception('Unable to save synonymy entry for family: ' . $model_taxFamilies->family . ' (' . var_export($model_taxSynonymyFamily->getErrors(), true) . ')');
            }
            
            // find all genus entries for the current family
            $models_taxGenera = TaxGenera::model()->findAllByAttributes(array(
                'familyID' => $model_taxFamilies->familyID
            ));
            foreach( $models_taxGenera as $j => $model_taxGenera ) {
                print("Processing genus " . ($j + 1) . "/" . count($models_taxGenera) . "\r");
                
                // ignore family entry in genus table
                if( $model_taxGenera->genID == $model_taxFamiliesGenera->genID ) continue;
                
                // find the species entries for this genus
                $model_taxGeneraSpecies = TaxSpecies::model()->findByAttributes(array(
                    'genID' => $model_taxGenera->genID,
                    'tax_rankID' => 7
                ));
                if( $model_taxGeneraSpecies == NULL ) {
                    //throw new Exception('Unable to find species entry for genus: ' . $model_taxGenera->genus);
                    print('Unable to find species entry for genus: ' . $model_taxGenera->genus . " - ignoring\n");
                    continue;
                }
                
                // now add the species entry of the genus as accepted name...
                $model_taxSynonymyGenus = new TaxSynonymy();
                $model_taxSynonymyGenus->taxonID = $model_taxGeneraSpecies->taxonID;
                $model_taxSynonymyGenus->userID = 0;
                $model_taxSynonymyGenus->timestamp = new CDbExpression('NOW()');
                $model_taxSynonymyGenus->source_citationID = Yii::app()->params['jacqClassificationCitationId'];
                $model_taxSynonymyGenus->source = 'literature';
                if( !$model_taxSynonymyGenus->save() ) {
                    throw new Exception('Unable to save synonymy entry for genus: ' . $model_taxGenera->genus . ' (' . var_export($model_taxSynonymyGenus->getErrors(), true) . ')');
                }
                // ...and add the family as parent
                $model_taxClassificationGenus = new TaxClassification();
                $model_taxClassificationGenus->tax_syn_ID = $model_taxSynonymyGenus->tax_syn_ID;
                $model_taxClassificationGenus->parent_taxonID = $model_taxFamiliesSpecies->taxonID;
                if( !$model_taxClassificationGenus->save() ) {
                    throw new Exception('Unable to save classification entry for genus: ' . $model_taxGenera->genus . ' (' . var_export($model_taxClassificationGenus->getErrors(), true) . ')');
                }
            }
            print("\n");
        }
        
        print("Done!\n");
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