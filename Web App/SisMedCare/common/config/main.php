<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
            'cachePath' => dirname(__DIR__) . '/runtime/cache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ]
    ],
];
