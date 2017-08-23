<?php

$app['DATABASE_CHARSET']  = 'utf8';
$app['DATABASE_USERNAME'] = 'root';
$app['DATABASE_PASSWORD'] = '';
$app['DATABASE_DRIVER']   = 'pdo_mysql';
$app['DATABASE_PORT']     = 3306;

$app['DATABASE'] = [
    'global'    => [
        'dbname'     => 'web_api_test',
        'masterHost' => 'localhost',
        'slaveHost'  => ['localhost', 'localhost'],
        'tables'     => [
            'user_masters',
            'user_params',
            'user_tokens'
        ],
    ]
];
