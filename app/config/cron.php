<?php
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Mediabox Storage',
    'charset'=>'utf-8',

    'import'=>array(
        'application.models.*',
        'application.components.*',
    ),

    'components'=>array(
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=mediabox_storage',
            'emulatePrepare' => true,
            'username' => 'mediabox',
            'password' => 'mediabox',
            'charset' => 'utf8',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
            ),
        ),
        'viewRenderer' => array(
            'class' => 'ext.ETwigViewRenderer',
            'fileExtension' => '.html',
            'options' => array(
                'autoescape' => true,
            )
        ),
    ),

    'params'=>array(
    ),
);