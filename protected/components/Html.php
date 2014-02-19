<?php

class Html extends CHtml {
    /**
     * Same as CHtml::listData but sorts the result before returning
     * @param type $models
     * @param type $valueField
     * @param type $textField
     * @return array
     */
    public static function listDataSorted($models, $valueField, $textField) {
        $listData = parent::listData($models, $valueField, $textField, '');
        asort($listData);
        
        return $listData;
    }
}