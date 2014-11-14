<?php

class SiteController extends JacqController {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $this->actionLogin();
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(array('/livingPlant'));
        }

        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->singleSignOnLegacy();
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        // logout for singleSignOnLegacy
        Yii::app()->dbHerbarInputLog
            ->createCommand('UPDATE tbl_herbardb_users SET login = NULL WHERE userID = :userID')
            ->bindValue(':userID', Yii::app()->session['uid'], PDO::PARAM_INT)
            ->execute();

        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    private function singleSignOnLegacy() {
//        Yii::log(Yii::app()->user->name);
        $dbRow = Yii::app()->dbHerbarInputLog
                    ->createCommand()
                        ->select('*')
                        ->from('tbl_herbardb_users u, tbl_herbardb_groups g')
                        ->where(
                                array(
                                    'AND',
                                    'u.groupID = g.groupID',
                                    'u.username = :username'
                                ),
                                array(':username' => Yii::app()->user->name)
                        )
                        ->queryRow();
        if ($dbRow) {
            $parts = explode('@', Yii::app()->params['singleSignOnLegacy']);
            Yii::app()->session['username']      = $parts[0];
            Yii::app()->session['password']      = $parts[1];
            Yii::app()->session['uid']           = $dbRow['userID'];
            Yii::app()->session['gid']           = $dbRow['groupID'];
            Yii::app()->session['sid']           = intval($dbRow['source_id']);
            Yii::app()->session['editFamily']    = $dbRow['editFamily'];
            Yii::app()->session['editControl']   = $dbRow['species']
                                                 + $dbRow['author']           *     0x2
                                                 + $dbRow['epithet']          *     0x4
                                                 + $dbRow['genera']           *     0x8
                                                 + $dbRow['family']           *    0x10
                                                 + $dbRow['lit']              *    0x20
                                                 + $dbRow['litAuthor']        *    0x40
                                                 + $dbRow['litPer']           *    0x80
                                                 + $dbRow['litPub']           *   0x100
                                                 + $dbRow['index']            *   0x200
                                                 + $dbRow['type']             *   0x400
                                                 + $dbRow['collIns']          *   0x800
                                                 + $dbRow['collUpd']          *  0x1000
                                                 + $dbRow['specim']           *  0x2000
                                                 + $dbRow['dt']               *  0x4000
                                                 + $dbRow['specimensTypes']   *  0x8000
                                                 + $dbRow['commonnameUpdate'] * 0x10000
                                                 + $dbRow['commonnameInsert'] * 0x20000;
            Yii::app()->session['linkControl']   = $dbRow['linkTaxon'];
            Yii::app()->session['editorControl'] = $dbRow['editor'];
            Yii::app()->dbHerbarInputLog
                ->createCommand('UPDATE tbl_herbardb_users SET login = NOW() WHERE userID = :userID')
                ->bindValue(':userID', $dbRow['userID'], PDO::PARAM_INT)
                ->execute();
        }
    }
}
