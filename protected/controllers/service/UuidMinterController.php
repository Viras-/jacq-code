<?php
/**
 * Service class for providing UUID minting utilities
 *
 * @author wkoller
 */
class UuidMinterController extends JacqController {
    /**
     * Static definition of type-ids for minting process
     * speeds up the minting
     */
    const SCIENTIFIC_NAME_TYPE_ID = 1;
    
    /**
     * Mint an id for a given scientific name
     * @param int $scientific_name_id ID of scientific name in internal system
     * @return string UUID for scientific name id
     * @throws Exception
     */
    public function scientificName($scientific_name_id) {
        return $this->mint(self::SCIENTIFIC_NAME_TYPE_ID, $scientific_name_id);
    }
    
    /**
     * Creates a new entry in the UUID minting table for the given type
     * @param int|string $type Type of UUID to mint, either the uuid_minter_type_id or the description as string
     * @param int $internal_id Internal ID of object to mint the UUID for
     * @return \UuidMinter
     * @throws Exception
     */
    protected function mint($type, $internal_id) {
        $type = trim($type);
        $internal_id = intval($internal_id);
        
        // check internal id for validity
        if( $internal_id <= 0) {
            throw new Exception("Invalid internal_id '" . $internal_id . "' passed");
        }

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
        
        // check if there is a previously minted UUID for this object
        $model_uuidMinter = UuidMinter::model()->findByAttributes(array(
            'internal_id' => $internal_id,
            'uuid_minter_type_id' => $type
        ));
        if( $model_uuidMinter != NULL ) {
            return $model_uuidMinter;
        }
        
        // create new entry in minter database
        $model_uuidMinter = new UuidMinter();
        $model_uuidMinter->uuid_minter_type_id = $type;
        $model_uuidMinter->internal_id = $internal_id;
        $model_uuidMinter->uuid = new CDbExpression("UUID()");
        $model_uuidMinter->save();
        // we need to refresh this record, since the UUID is generated in the database
        $model_uuidMinter->refresh();
        
        return $model_uuidMinter;
    }
    
    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }
}
