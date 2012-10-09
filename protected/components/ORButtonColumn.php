<?php
/**
 * Button class for organisations grid (based on role rights)
 *
 * @author wkoller
 */
class ORButtonColumn extends CButtonColumn {
    public function __construct($grid) {
        $this->template = '';
        
        if( Yii::app()->user->checkAccess('oprtn_createOrganisation') ) {
            $this->template .= '{update}';
        }
        if( Yii::app()->user->checkAccess('oprtn_deleteOrganisation') ) {
            $this->template .= ' {delete}';
        }

        parent::__construct($grid);
    }
}
