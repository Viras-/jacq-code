<?php

class JSONClassificationBrowserController extends Controller {
    /**
     * Return connection to herbarinput database
     * @return CDbConnection 
     */
    private function getDbHerbarInput() {
        return Yii::app()->dbHerbarInput;
    }

    /**
     * Fetch a list of all references (which have a classification attached)
     * @param string $p_referenceType Type of references to return (citation, person, service, specimen)
     * @return array References information
     */
    public function japiReferences( $p_referenceType ) {
        $references_field = '';
        
        // make sure we have a valid parameter
        switch( $p_referenceType ) {
            case 'person':
                $references_field = 'source_person_ID';
                break;
            case 'service':
                $references_field = 'source_serviceID';
                break;
            case 'specimen':
                $references_field = 'source_specimenID';
                break;
            case 'citation':
            default:
                $references_field = 'source_citationID';
                break;
        }
        
        // get reference to input database
        $db = $this->getDbHerbarInput();
        
        // fetch all available references
        $dbCommand = $db->createCommand();
        $dbCommand->select($references_field)->from('tbl_tax_synonymy')->group($references_field);
        
        return array();
    }
    
    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }
}
