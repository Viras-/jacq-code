<?php
/**
 * Service class for providing UUID minting utilities
 *
 * @author wkoller
 */
class UuidMinterController extends JacqController {
    public function scientificName() {
        return $this->mint('scientific_name');
    }
    
    /**
     * Creates a new entry in the UUID minting table for the given type
     * @param int|string $type Type of UUID to mint, either the uuid_minter_type_id or the description as string
     * @return \UuidMinter
     * @throws Exception
     */
    protected function mint($type) {
        $type = trim($type);

        // if we do not get passed an id, treat it as description string
        if( !is_int($type) ) {
            $model_uuidMinterType = UuidMinterType::model()->findByAttributes(array(
                'description' => $type
            ));
            
            // check if we found a valid entry
            if( $model_uuidMinterType == NULL ) {
                throw new Exception("Invalid UUID type '" . $type . "' requested");
            }
            
            // remember actual integer id
            $type = $model_uuidMinterType->uuid_minter_type_id;
        }
        
        // create new entry in minter database
        $model_uuidMinter = new UuidMinter();
        $model_uuidMinter->uuid_minter_type_id = $type;
        $model_uuidMinter->uuid = new CDbExpression("UUID()");
        $model_uuidMinter->save();
        
        return $model_uuidMinter;
    }
    
    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }
}
