<?php
/**
 * Yii behavior to manage automatic logging of changed CActiveRecords
 * including automatic creation of logging tables if they do not exist yet 
 */
class ActiveRecordLogableBehavior extends CActiveRecordBehavior {
    private $oldattributes = array();
    
    /**
     * save a copy of old data to logging table after saving new record
     */
    public function afterSave($event) {
        // new records do not require logging
        if( $this->owner->isNewRecord ) return;
        
        // prepare attributes
        $attributes = $this->oldattributes;
        $bLog = false;  // only log if this is a new entry, or at least one attribute changed
        
        // check if at least one attribute changed
        foreach( $attributes as $name => $value) {
            if( $this->owner->attributes[$name] != $value ) {
                $bLog = true;
                break;
            }
        }
        
        // add other logging specific info
        $attributes['log_action'] = 'update';
        $attributes['log_user'] = Yii::app()->user->id;
        
        // add log entry
        if( $bLog ) {
            $dbLog = $this->getDbLog();
            $dbLog->createCommand()->insert($this->getLogTable(), $attributes);
        }
    }
    
    /**
     * save a copy (of old data) to log table after deleting
     */
    public function afterDelete($event) {
        // prepare attributes
        $attributes = $this->oldattributes;

        // add other logging specific info
        $attributes['log_action'] = 'delete';
        $attributes['log_user'] = Yii::app()->user->id;

        // add log entry
        $dbLog = $this->getDbLog();
        $dbLog->createCommand()->insert($this->getLogTable(), $attributes);
    }

    /**
     * Return connection to jacq_log database
     * @return CDbConnection 
     */
    private function getDbLog() {
        return Yii::app()->dbLog;
    }
    
    /**
     * construct log table name
     * @return string name of logging table for activerecord
     */
    private function getLogTable() {
        return $this->owner->getTableSchema()->name . '_log';
    }
    
    /**
     * make sure old attributes are saved for internal use
     * and logging table exists
     */
    public function afterFind($event) {
        $this->oldattributes = $this->owner->getAttributes();
        
        // check if we have a logging table for this CActiveRecord
        $tblName_log = $this->getLogTable();
        $dbLog = $this->getDbLog();
        $dbLog->getSchema()->refresh();
        $dbLog_tables = $dbLog->getSchema()->tableNames;
        $bLogFound = false;
        foreach($dbLog_tables as $dbLog_table) {
            if($dbLog_table == $tblName_log) {
                $bLogFound = true;
                break;
            }
        }
        
        // if not, create a copy of the table structure and add additional fields
        if( !$bLogFound ) {
            // cycle through original structure and add column definitions from it
            $columns_log = array();
            foreach($this->owner->getTableSchema()->columns AS $column_name => $column_info) {
                // original timestamps should not be updated anymore in the logging table, so make them standard ones
                if( stristr($column_info->dbType, 'TIMESTAMP') ) {
                    $columns_log[$column_name] = 'TIMESTAMP NULL DEFAULT NULL';
                }
                else {
                    $columns_log[$column_name] = $column_info->dbType;
                }
            }
            
            // add additional log columns
            $columns_log['log_id'] = "INT NOT NULL AUTO_INCREMENT";
            $columns_log[] = "PRIMARY KEY (log_id)";
            $columns_log['log_time'] = "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
            $columns_log['log_user'] = "INT NOT NULL DEFAULT 0";
            $columns_log['log_action'] = "ENUM('update', 'delete') DEFAULT NULL";
            
            // finally create the table
            $dbLog->createCommand()->createTable($tblName_log, $columns_log);
        }
    }
}
