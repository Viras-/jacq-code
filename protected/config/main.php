<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'JACQ',
    'language' => 'de',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.extensions.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'gii',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1', '192.168.56.1'),
        ),
        'rbam' => array(
            'rbacManagerRole' => 'rbacManager',
            'authItemsManagerRole' => 'rbacManager',
            'authAssignmentsManagerRole' => 'rbacManager',
            'authenticatedRole' => '',
            'guestRole' => 'guest',
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        /*
          'urlManager'=>array(
          'urlFormat'=>'path',
          'rules'=>array(
          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
          ),
          ),
         */
        /* 'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ), */
        // uncomment the following to use a MySQL database

        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=jacq_input',
            'emulatePrepare' => true,
            'username' => 'jacq_input',
            'password' => 'jacq_input',
            'charset' => 'utf8',
        ),
        'dbHerbarInput' => array(
            'connectionString' => 'mysql:host=localhost;dbname=herbarinput',
            'emulatePrepare' => true,
            'username' => 'herbarinput',
            'password' => 'herbarinput',
            'charset' => 'utf8',
            'class' => 'CDbConnection'
        ),
        'dbHerbarView' => array(
            'connectionString' => 'mysql:host=localhost;dbname=herbar_view',
            'emulatePrepare' => true,
            'username' => 'herbarinput',
            'password' => 'herbarinput',
            'charset' => 'utf8',
            'class' => 'CDbConnection'
        ),
        'dbLog' => array(
            'connectionString' => 'mysql:host=localhost;dbname=jacq_log',
            'emulatePrepare' => true,
            'username' => 'jacq_log',
            'password' => 'jacq_log',
            'charset' => 'utf8',
            'class' => 'CDbConnection'
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'frmwrk_AuthItem',
            'itemChildTable' => 'frmwrk_AuthItemChild',
            'assignmentTable' => 'frmwrk_AuthAssignment',
            'defaultRoles' => array('guest')
        )
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'w.koller@senegate.at',
    ),
);
