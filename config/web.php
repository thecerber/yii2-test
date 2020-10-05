<?php

use yii\web\Response;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'app\components\Bootstrap',
    ],
    'components' => [
        'request' => [
            'class' => 'app\components\Request',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            'enableCsrfCookie' => false,
        ],
        'response' => [
            'class' => Response::class,
            'format' => Response::FORMAT_JSON,

            'on beforeSend' => function ($event) {
                /** @var Response $response */
                $response = $event->sender;

                if ($response->isSuccessful === false && $response->data !== null) {
                    $response->data = [
                        'status' => 'error',
                        'message' => isset($response->data['message']) ? $response->data['message'] : null,
                    ];
                }
            },
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\db\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
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
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*'],
    ];
}

return $config;
