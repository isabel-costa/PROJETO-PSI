<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',
        ],
    ],
    'components' => [
        'formatter' => [
            'class' => yii\i18n\Formatter::class,
            'dateFormat' => 'php:d-m-Y',
            'datetimeFormat' => 'php:d-m-Y H:i',
            'timeFormat' => 'php:H:i',
            'defaultTimeZone' => 'Europe/Lisbon',
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@vendor/hail812/yii2-adminlte3/src/views' => '@app/views'
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/auth'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST registar' => 'registar'
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/consulta'],
                    'pluralize' => true,
                    'extraPatterns' => [
                        'GET futuras' => 'futuras',
                        'GET passadas' => 'passadas',
                        'POST solicitar' => 'solicitar',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/paciente'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET' => 'view',
                        'PUT' => 'update',
                        'GET alergias' => 'alergias',
                        'POST alergias' => 'create-alergias',
                        'PUT alergias' => 'update-alergias',
                        'DELETE alergias' => 'delete-alergias',
                        'GET doencas' => 'doencas',
                        'POST doencas' => 'create-doencas',
                        'PUT doencas' => 'update-doencas',
                        'DELETE doencas' => 'delete-doencas'
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/medico'],
                    'pluralize' => true,
                    'extraPatterns' => [
                        'GET medicos' => 'medicos'
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/prescricao'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET' => 'index',
                        'GET {id}' => 'view'
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/prescricao-medicamento'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET prescricao/<prescricao_id>' => 'prescricao'
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/registo-toma'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET pendentes' => 'pendentes',
                        'GET tomadas' => 'tomadas',
                        'POST marcar/{id}' => 'marcar'
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];