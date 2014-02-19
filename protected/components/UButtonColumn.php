<?php
/**
 * Button class for user grid (based on role rights)
 *
 * @author wkoller
 */
class UButtonColumn extends CButtonColumn {
    public function __construct($grid) {
        $this->template = '';
        
        if( Yii::app()->user->checkAccess('oprtn_createUser') ) {
            $this->template .= '{update}';
        }
        if( Yii::app()->user->checkAccess('oprtn_deleteUser') ) {
            $this->template .= ' {delete}';
        }

        parent::__construct($grid);
    }
}
