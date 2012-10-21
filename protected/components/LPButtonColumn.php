<?php
/**
 * Button class for LivingPlants grid (based on role rights)
 *
 * @author wkoller
 */
class LPButtonColumn extends CButtonColumn {
    public function __construct($grid) {
        $this->template = '';
        
        if( Yii::app()->user->checkAccess('oprtn_readLivingplant') ) {
            $this->template .= '{view}';
        }
        if( Yii::app()->user->checkAccess('oprtn_createLivingplant') ) {
            $this->template .= '{update}';
        }
        if( Yii::app()->user->checkAccess('oprtn_deleteLivingplant') ) {
            $this->template .= ' {delete}';
        }

        parent::__construct($grid);
    }
}
