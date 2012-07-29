<?php

class AutoCompleteController extends Controller {

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

    private function serviceOutput($output) {
        header('Content-type: application/json');
        echo CJSON::encode($output);
        Yii::app()->end();
    }

    /**
     * Search for fitting taxon names and return them 
     */
    public function actionTaxon() {
        $term = $_GET['term'];
        $pieces = explode(' ', $term);

        // Check for valid input
        if (count($pieces) <= 0 || empty($pieces[0])) {
            die('Invalid request');
        }

        // Construct default search criteria
        $where_fields = array('AND', 'ts.external = 0', 'tg.genus LIKE :genus');
        $where_fields_data = array(':genus' => $pieces[0] . '%');

        // Check if we search the first epithet as well
        if (count($pieces) >= 2 && !empty($pieces[1])) {
            $where_fields[] = 'te0.epithet LIKE :epithet0';
            $where_fields_data[':epithet0'] = $pieces[1] . '%';
        }

        $dbHerbarInput = $this->getDbHerbarInput();
        $command = $dbHerbarInput->createCommand()
                ->select("ts.taxonID")
                ->from("tbl_tax_species ts")
                ->leftJoin("tbl_tax_epithets te0", "te0.epithetID = ts.speciesID")
                ->leftJoin("tbl_tax_epithets te1", "te1.epithetID = ts.subspeciesID")
                ->leftJoin("tbl_tax_epithets te2", "te2.epithetID = ts.varietyID")
                ->leftJoin("tbl_tax_epithets te3", "te3.epithetID = ts.subvarietyID")
                ->leftJoin("tbl_tax_epithets te4", "te4.epithetID = ts.formaID")
                ->leftJoin("tbl_tax_epithets te5", "te5.epithetID = ts.subformaID")
                ->leftJoin("tbl_tax_genera tg", "tg.genID = ts.genID")
                ->where($where_fields, $where_fields_data);

        $rows = $command->queryAll();

        $results = array();
        foreach ($rows as $row) {
            $taxonID = $row['taxonID'];

            $scientificName = $this->getTaxonName($taxonID);

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
        
        // Construct service URL
        $geonamesUrl = "http://api.geonames.org/searchJSON?formatted=true&maxRows=10&lang=de&username=demo&style=medium&name=" . urlencode($term);
        // Fetch service response
        $service_response = file_get_contents($geonamesUrl);
        if( $service_response ) {
            // Decode data
            $service_data = json_decode($service_response, true);

            // Construct answer array with data from table
            $results = array();
            foreach ($service_data['geonames'] as $geoname ) {
                $display =$geoname['name'] . ' (' . $geoname['countryName'] . ')';
                
                $results[] = array(
                    "label" => $display,
                    "value" => $display,
                    "id" => $geoname['geonameId'],
                );
            }

            // Output results as service response
            $this->serviceOutput($results);
        }
    }

    /**
     * Auto-completer action for person names 
     */
    public function actionPerson() {
        // Clean up passed person name
        $term = trim($_GET['term']);
        
        // We want at least two letters
        if( strlen($term) <= 0 ) return;

        // Fetch all possible persons
        $dbHerbarInput = $this->getDbHerbarInput();
        $command = $dbHerbarInput->createCommand()
                ->select("SammlerID, Sammler")
                ->from("tbl_collector")
                ->where('Sammler LIKE :Sammler', array(':Sammler' => $term . '%'));
        $rows = $command->queryAll();

        // Construct answer array with data from table
        $results = array();
        foreach ($rows as $row) {
            $results[] = array(
                "label" => $row['Sammler'],
                "value" => $row['Sammler'],
                "id" => $row['SammlerID'],
            );
        }

        // Output results as service response
        $this->serviceOutput($results);
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('taxon', 'location', 'person'),
                'users' => array('@'),
            ),
            array('deny', // deny all users by default
                'users' => array('*'),
            ),
        );
    }

}
