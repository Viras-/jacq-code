<?php
/**
 * Button class for tree record file grid (based on role rights)
 *
 * @author wkoller
 */
class TRFButtonColumn extends CButtonColumn {
    public function __construct($grid) {
        $this->template = '';
        
        if( Yii::app()->user->checkAccess('oprtn_createTreeRecordFile') ) {
            $this->template .= '{update}';
        }
        if( Yii::app()->user->checkAccess('oprtn_deleteTreeRecordFile') ) {
            $this->template .= ' {delete}';
        }

        parent::__construct($grid);
    }
}
