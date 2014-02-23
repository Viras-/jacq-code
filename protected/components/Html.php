<?php

class Html extends CHtml {
    /**
     * Same as CHtml::listData but sorts the result before returning
     * @param type $models
     * @param type $valueField
     * @param type $textField
     * @param boolean $bAddNone Add an entry for "None" to the possible values
     * @return array
     */
    public static function listDataSorted($models, $valueField, $textField, $bAddNone = false) {
        $listData = parent::listData($models, $valueField, $textField, '');
        asort($listData);
        
        // add empty entry?
        if( $bAddNone ) {
            $listData = array( '' => Yii::t('jacq_types', 'none') ) + $listData;
        }
        
        return $listData;
    }
}
