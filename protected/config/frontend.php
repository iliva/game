<?php

return CMap::mergeArray(

    require_once(dirname(__FILE__).'/main.php'),
    
    array( 
         
        // стандартный контроллер
        'defaultController' => 'site',

       // 'theme'=>'basic',

        'import'=>array( 
            'application.extensions.*', 
        ), 

        'modules'=>array(
 
        ),
       
        // компоненты
        'components'=>array( 

            'mailer' => array(
              'class' => 'application.extensions.mailer.EMailer',
              //'pathViews' => 'application.views.email',
              //'pathLayouts' => 'application.views.email.layouts'
            ), 
            'urlManager'=>array(
                'class'             => 'application.components.UrlManager',
                'urlFormat'         => 'path',
                'showScriptName'    => false,
                'rules'             => array( 
                                        '<language:\w+>{2}/getDocument' => 'site/GetDocument', 
                                        '<language:\w+>{2}/ajax/<action:\w+>'    => 'ajax/<action>',                                     ),
 
            ), 
            'errorHandler'=>array(
                'errorAction'=>'site/error',
            ),

        ),
    )
);