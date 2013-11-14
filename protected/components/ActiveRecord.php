<?php

/**
 * Description of ActiveRecord
 *
 * @author wkoller
 */
class ActiveRecord extends CActiveRecord {
    /**
     * Constant for defining the translate suffix for triggering automatic translation of properties
     */
    const TRANSLATE_SUFFIX = "Translated";
    
    /**
     * Overwrite isset function to provide automatic translation capabilities
     */
    public function __isset($name) {
        // split name by "Translated" to check if term needs to be translated
        if (strpos($name, self::TRANSLATE_SUFFIX) !== false) {
            $name = str_replace(self::TRANSLATE_SUFFIX, "", $name);
            
            if( parent::__isset($name) ) {
                return true;
            }
            
            return false;
        }

        return parent::__isset($name);
    }

    /**
     * Overwrite get function to provide automatic translation capabilities
     */
    public function __get($name) {
        // split name by "Translated" to check if term needs to be translated
        if (strpos($name, self::TRANSLATE_SUFFIX) !== false) {
            $name = str_replace(self::TRANSLATE_SUFFIX, "", $name);

            return Yii::t('jacq', parent::__get($name));
        }

        return parent::__get($name);
    }

    /**
     * Attached global behaviours to all ActiveRecords
     */
    public function behaviors() {
        return array(
            'ActiveRecordLogableBehavior' => 'application.behaviors.ActiveRecordLogableBehavior'
        );
    }
}
