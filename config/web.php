<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'mkt-charts',
	'name' => 'MarketingCharts',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
	
	
//	'modules' => [
//		'user' => [
//			'class' => 'amnah\yii2\user\Module',
//			// set custom module properties here ...
//		],
//	],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
    ],
    
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
	        'enableCookieValidation' => true,
	        'enableCsrfValidation' => true,
	        'cookieValidationKey' => 'xxxxxxx',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
	
	    'mailer' => [
		    'class' => 'yii\swiftmailer\Mailer',
		    'viewPath' => '@app/mailer',
		    'useFileTransport' => false,
		    'messageConfig' => [
			    'from' => ['admin@website.com' => 'Admin'], // this is needed for sending emails
			    'charset' => 'UTF-8',
		    ],
		    'transport' => [
			    'class' => 'Swift_SmtpTransport',
			    'host' => 'smtp.googlemail.com',
			    'username' => 'test@slabire-sanatoasa.ro',
			    'password' => 'test#1',
			    'port' => '465',
			    'encryption' => 'tls',
		    ],
	    ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            	'dashboard' => 'site/index',
            ],
        ],
		
    ],
	'as beforeRequest' => [
		'class' => 'yii\filters\AccessControl',
		'rules' => [
			[
				'allow' => true,
				'actions' => ['login'],
			],
			[
				'allow' => true,
				'roles' => ['@'],
			],
		],
		'denyCallback' => function () {
			return Yii::$app->response->redirect(['site/login']);
		},
	],
	
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.56.*'],
    ];
    
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.56.*'],
    ];
}

return $config;
