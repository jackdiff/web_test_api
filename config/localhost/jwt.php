<?php
use Hatame\Provider\SecureUserServiceProvider;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\User;

$app['security.jwt'] = [
    'secret_key' => 'x6qvGJ1mxBUU6t9WdpOB',
    'life_time'  => 86400,
    'options'    => [
        'username_claim' => 'id', // default name, option specifying claim containing username
        'header_name' => 'X-Access-Token', // default null, option for usage normal oauth2 header
        'token_prefix' => 'Bearer',
    ]
];
$app['users'] = function () use ($app) {
    return new SecureUserServiceProvider($app['database']);
};