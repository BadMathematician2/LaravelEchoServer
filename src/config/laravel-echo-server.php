<?php

return [
    'authHost' => 'http://localhost',
    'authEndpoint' => '/broadcasting/auth',
    'clients' => [],
    'database' => 'redis',
    'databaseConfig' => [
        'redis' => [],
        'sqlite' => ['databasePath' => '/database/laravel-echo-server.sqlite']
    ],
    'devMode' => null,
    'host' => null,
    'port' => '6001',
    'protocol' => 'http',
    'sockerio' => [],
    'secureOptions' => 67108864,
    'sslCertPath' => '',
    'sslKeyPath' => '',
    'sslCertChainPath' => '',
    'sslPassphrase' => '',
    'subscribers' => [
        'http' => true,
        'redis' => true
    ],
    'apiOriginAllow' => [
        'allowCors' => false,
        'allowOrigin' => '',
        'allowMethods' => '',
        'allowHeaders' => ''
    ]
];
