<?php
/**
 * Helper class for designing JSON services
 */
class JSONServiceController extends Controller {
    /**
     * Helper function for printing JSON conform output
     * @param string $output
     */
    protected function serviceOutput($output) {
        header('Content-type: application/json');
        echo CJSON::encode($output);
        Yii::app()->end();
    }
}
