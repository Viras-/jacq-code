<?php
/**
 * Description of ActiveRecord
 *
 * @author wkoller
 */
class ActiveRecord extends CActiveRecord {
    public function behaviors() {
        return array(
                    'ActiveRecordLogableBehavior' => 'application.behaviors.ActiveRecordLogableBehavior'
                );
    }
}
