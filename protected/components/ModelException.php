<?php
/**
 * Helper exception class for model errors
 */
class ModelException extends Exception {
    public function __construct($message, $activeRecord) {
        $message .= ': ' . var_export($activeRecord->getErrors(), true);
        
        parent::__construct($message);
    }
}
