<?php

class ScientificNameInformationController extends Controller {
    /**
     * Update / Create a scientific name information entry
     * @param int $scientific_name_id
     */
    public function actionAjaxUpdate($scientific_name_id) {
        // check for valid input
        $scientific_name_id = intval($scientific_name_id);
        if( $scientific_name_id <= 0 ) return;
        
        // try to load the model
        $model_scientificNameInformation = ScientificNameInformation::model()->findByPk($scientific_name_id);
        if( $model_scientificNameInformation == NULL ) {
            $model_scientificNameInformation = new ScientificNameInformation();
            $model_scientificNameInformation->scientific_name_id = $scientific_name_id;
        }
        
        // check if form was submitted, if yes store the information
        if(isset($_POST['ScientificNameInformation'])) {
            $model_scientificNameInformation->attributes = $_POST['ScientificNameInformation'];
            
            // check for successfull saving
            if( $model_scientificNameInformation->save() ) {
            }
        }
        
        // render the update form
        $this->renderPartial('update', array(
            'model_scientificNameInformation' => $model_scientificNameInformation,
        ));
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // updating
                'actions' => array('ajaxUpdate'),
                'roles' => array('oprtn_createScientificNameInformation'),
            ),
            array('deny', // deny all users by default
                'users' => array('*'),
            ),
        );
    }
}
