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
            
            // create / update associated cultivar entries
            if( isset($_POST['Cultivar']) ) {
                foreach($_POST['Cultivar'] as $i => $cultivar) {
                    // make sure we have a clean integer as id
                    $cultivar['cultivar_id'] = intval($cultivar['cultivar_id']);

                    // check for "deleted" entry
                    if( $cultivar['delete'] > 0 ) {
                        if($cultivar['cultivar_id'] > 0) {
                            Cultivar::model()->deleteByPk($cultivar['cultivar_id']);
                        }
                        continue;
                    }

                    // check if this is an existing entry
                    $model_cultivar = Cultivar::model()->findByPk($cultivar['cultivar_id']);
                    if( $model_cultivar == NULL ) {
                        $model_cultivar = new Cultivar();
                    }

                    // assign attributes and save it
                    $model_cultivar->attributes = $cultivar;
                    $model_cultivar->scientific_name_id = $scientific_name_id;
                    $model_cultivar->save();
                }
            }
        }
        
        // render the update form
        $this->renderPartial('update', array(
            'model_scientificNameInformation' => $model_scientificNameInformation,
        ));
    }

    /**
     * renders form for entering a new cultivar
     */
    public function actionAjaxCultivar() {
        $model_cultivar = new Cultivar();
        
        $this->renderPartial('form_cultivar', array(
            'model_cultivar' => $model_cultivar
        ), false, true);
    }
    
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // updating
                'actions' => array('ajaxUpdate', 'ajaxCultivar'),
                'roles' => array('oprtn_createScientificNameInformation'),
            ),
            array('deny', // deny all users by default
                'users' => array('*'),
            ),
        );
    }
}
