<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'	=> dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name' 		=> 'Game',

	
	// preloading 'log' component
	'preload' 	=> array('log'),
	
	'sourceLanguage'=> 'en',
	'language'		=> 'ua',

	// autoloading model and component classes
	'import' => array(
		'application.models.*',
		'application.components.*',
		'application.extensions.EAjaxUpload.*', 
		'application.helpers.*', 
	),

	// стандартный контроллер
    //'defaultController' => 'BaseController',

 
	// стандартный контроллер
    //'defaultController' => 'BaseController',

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		 /*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			//'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			//'ipFilters'=>array('127.0.0.1','::1'),

            'generatorPaths'=>array(
                'application.gii',   // псевдоним пути
            ),
        
		),
		*/
		 
	),

	// используемые приложением поведения
	'behaviors'=>array(
		'runEnd'=>array(
			'class'=>'application.behaviors.WebApplicationEndBehavior',
		),
	),

	// application components
	'components'=>array(

		'urlManager'=>array(
	        'urlFormat'=>'path',
	        'rules'=>array(
	            //'gii'=>'gii',
	            //'gii/<controller:\w+>'=>'gii/<controller>',
	            //'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
	          
	        ),
	    ),
		
        'cacheFile'=>array(
            'class'=>'system.caching.CFileCache', // используем кэш на файлах
        ), 		
		
		'authManager'=>array(
            'class'=>'CDbAuthManager',
            'connectionID'=>'db',
            'defaultRoles'=>array('guest'),
            'itemTable'=>'smart_auth_item',
            'itemChildTable'=>'smart_auth_item_child',
            'assignmentTable'=>'smart_auth_assignment',
        ),

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		'eauth' => array(
            'class' => 'application.extensions.eauth.EAuth',
            'popup' => true, // Use the popup window instead of redirecting. 
        ),
	 
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=game',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
		/*	'charset' => 'utf8', */
		),	
		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages				 
				//array(
					//'class'=>'CWebLogRoute',
				//),				 
			),
		),
		
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	 
	'params'=>array(
		// this is used in contact page 
		'webRoot' 				=> dir(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'), 
		'defaultLang'			=> 'ua',
		'createUrlLang'			=> true,
		'isMobile' 				=> false,
		'site_email' 			=> 'ira.left@gmail.com',
		'manager_email' 		=> 'ira.left@gmail.com',		
	),
	 
 
);