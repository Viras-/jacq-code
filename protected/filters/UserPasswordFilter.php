<?php
/**
 * Global filter for redirecting the user to the profile page if a password change is required
 *
 * @author wkoller
 */
class UserPasswordFilter extends CFilter {
    /**
     * check if the user has to change his/her password
     * @param CFilterChain $filterChain
     */
    protected function preFilter($filterChain) {
        $model_user = User::model()->findByPk(Yii::app()->user->getId());
        
        if( $model_user != NULL ) {
            // check if we have to redirect
            if( ($filterChain->controller->getId() != 'user'
                || $filterChain->action->getId() != 'profile')
                && $model_user->force_password_change ) {
                $filterChain->controller->redirect(array('user/profile'));
                
                return false;
            }
        }
        
        return true;
    }
}
