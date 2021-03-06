<?php

/**
 * Handles the auto-complete functions
 */
class AutoCompleteController extends JSONServiceController {

    /**
     * Return connection to herbarinput database
     * @return CDbConnection
     */
    private function getDbHerbarInput() {
        return Yii::app()->dbHerbarInput;
    }

    /**
     * Return connection to herbar_view database
     * @return CDbConnection
     */
    private function getDbHerbarView() {
        return Yii::app()->dbHerbarView;
    }

    /**
     * Get the scientific name for a given taxon ID
     * @param int $taxonID
     * @return string
     */
    private function getTaxonName($taxonID) {
        $dbHerbarView = $this->getDbHerbarView();
        $command = $dbHerbarView->createCommand("SELECT GetScientificName( " . $taxonID . ", 0 ) AS 'ScientificName'");
        $scientificNames = $command->queryAll();

        return $scientificNames[0]['ScientificName'];
    }

    /**
     * Search for fitting scientific names and return them
     */
    public function actionScientificName($term) {
        $pieces = explode(' ', $term);

        // Check for valid input
        if (count($pieces) <= 0 || empty($pieces[0])) {
            die('Invalid request');
        }

        // Construct default search criteria
        $where_fields = array('AND', 'ts.external = 0', 'tg.genus LIKE :genus');
        $where_fields_data = array(':genus' => $pieces[0] . '%');

        // Create basic SQL-command;
        $dbHerbarInput = $this->getDbHerbarInput();
        $command = $dbHerbarInput->createCommand()
                ->select("ts.taxonID, herbar_view.GetScientificName( ts.taxonID, 0 ) AS ScientificName")
                ->from("tbl_tax_species ts")
                ->leftJoin("tbl_tax_genera tg", "tg.genID = ts.genID")
                ->order("ScientificName");

        // Check if we search the first epithet as well
        if (count($pieces) >= 2 && !empty($pieces[1])) {
            $command->leftJoin("tbl_tax_epithets te0", "te0.epithetID = ts.speciesID");

            $where_fields[] = 'te0.epithet LIKE :epithet0';
            $where_fields_data[':epithet0'] = $pieces[1] . '%';
        } else {
            $where_fields[] = 'ts.speciesID IS NULL';
        }

        // Add where condition
        $command->where($where_fields, $where_fields_data);

        $rows = $command->queryAll();

        $results = array();
        foreach ($rows as $row) {
            $taxonID = $row['taxonID'];
            $scientificName = $row['ScientificName'];

            //$scientificName = $this->getTaxonName($taxonID);

            if (!empty($scientificName)) {
                $results[] = array(
                    "label" => $scientificName,
                    "value" => $scientificName,
                    "id" => $taxonID,
                );
            }
        }

        // Output results as service response
        $this->serviceOutput($results);
    }

    /**
     * Search for fitting location names (and query geonames if necessary)
     */
    public function actionLocation() {
        $term = trim($_GET['term']);
        $bGeonames = (isset($_GET['geonames'])) ? true : false;
        $results = array();
        $geonamesUrl = "http://api.geonames.org/searchJSON?maxRows=10&lang=de&username=wkoller&style=medium";

        if ($bGeonames) {
            // Construct service URL
            $geonamesUrl = $geonamesUrl . "&q=" . urlencode($term);
            // Fetch service response
            $service_response = file_get_contents($geonamesUrl);
            if ($service_response) {
                // Decode data
                $service_data = json_decode($service_response, true);

                // Save response data in location table
                foreach ($service_data['geonames'] as $geoname) {
                    // Check if we already have any entry
                    $model_location = null;
                    $model_locationGeonames = LocationGeonames::model()->find('geonameId=:geonameId', array(':geonameId' => $geoname['geonameId']));
                    if ($model_locationGeonames != null) {
                        $model_location = Location::model()->findByPk($model_locationGeonames->id);

                        // update model with new location description
                        $model_location->location = $geoname['name'] . ' (' . $geoname['adminName1'] . ', ' . $geoname['countryName'] . ')';
                        $model_location->save();
                    } else {
                        // Create location model & save it
                        $model_location = new Location;
                        $model_location->location = $geoname['name'] . ' (' . $geoname['adminName1'] . ', ' . $geoname['countryName'] . ')';
                        $model_location->save();
                        // Create according geonames model & save it as well
                        $model_locationGeonames = new LocationGeonames;
                        $model_locationGeonames->id = $model_location->id;
                        $model_locationGeonames->service_data = serialize($geoname);
                        $model_locationGeonames->geonameId = $geoname['geonameId'];
                        $model_locationGeonames->countryCode = $geoname['countryCode'];
                        $model_locationGeonames->save();
                    }

                    // Add response to results
                    $results[] = array(
                        "label" => $model_location->location,
                        "value" => $model_location->location,
                        "id" => $model_location->id,
                        "countryCode" => $model_locationGeonames->countryCode
                    );
                }
            }
        } else {
            // Find all fitting entries in location table
            $models_location = Location::model()->findAll('location LIKE :location', array(':location' => $term . '%'));
            if ($models_location != NULL) {
                foreach ($models_location as $model_location) {
                    $results[] = array(
                        "label" => $model_location->location,
                        "value" => $model_location->location,
                        "id" => $model_location->id,
                    );
                }
            }
        }

        // Output results as service response
        $this->serviceOutput($results);
    }

    /**
     * Auto-completer action for person names
     */
    public function actionPerson() {
        // Clean up passed person name
        $term = trim($_GET['term']);

        // We want at least two letters
        if (strlen($term) <= 0) {
            return;
        }

        // Fetch all possible persons
        $dbJacqInput = Yii::app()->db;
        $command = $dbJacqInput->createCommand()
                ->select("id, name")
                ->from("view_person")
                ->where(array('like', 'name', $term . '%'))
                ->group('name');
        $rows = $command->queryAll();

        // Construct answer array with data from table
        $results = array();
        foreach ($rows as $row) {
            // Get a fitting person entry
            $model_person = Person::getByName($row['name']);

            // Add resulting perosn model info to response
            $results[] = array(
                "label" => $model_person->name,
                "value" => $model_person->name,
                "id" => $model_person->id,
            );
        }

        // Output results as service response
        $this->serviceOutput($results);
    }

    /**
     * Autocompleter function for Acquisition-Source entries
     * @param string $term name of acquisition source entry to search for
     */
    public function actionAcquisitionSource($term) {
        $results = array();

        // search for entries containing the search term as name
        $dbCriteria = new CDbCriteria();
        $dbCriteria->addSearchCondition('name', $term);
        $models_acquisitionSource = AcquisitionSource::model()->findAll($dbCriteria);

        // add all found entries to the result
        foreach ($models_acquisitionSource as $model_acquisitionSource) {
            $results[] = array(
                'label' => $model_acquisitionSource->name,
                'value' => $model_acquisitionSource->name,
                'id' => $model_acquisitionSource->acquisition_source_id,
            );
        }

        // Output results as service response
        $this->serviceOutput($results);
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array('scientificName'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('location', 'person', 'acquisitionSource'),
                'users' => array('@'),
            ),
            array('deny', // deny all users by default
                'users' => array('*'),
            ),
        );
    }

}
