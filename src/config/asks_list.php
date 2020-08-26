<?php

return [
    [
        'key' => 'devMode',
        'value' => 'No',
        'ask' => 'Do you want to run this server in development mode? (yes/No)',
        'validator' => function ($value) { return $value === 'yes'; }
    ],
    [
        'key' => 'port',
        'value' => '6001',
        'ask' => 'Which port would you like to serve from?',
    ],
    [
        'key' => 'authHost',
        'value' => 'http://localhost',
        'ask' => 'Enter the host of your Laravel authentication server.',
    ],
    [
        'key' => 'protocol',
        'value' => 'http',
        'ask' => 'Will you be serving on http or https?',
        'validator' => function ($value) {
           return 'https' === $value;
        },
        'others' => [
            [
                'key' => 'sslCertPath',
                'value' => '',
                'ask' => 'Enter the path to your SSL cert file.',
                'validator' => function () {return true;},
            ],
            [
                'key' => 'sslKeyPath',
                'value' => '',
                'ask' => 'Enter the path to your SSL key file.',
                'validator' => function () {return true;},
            ],
        ]
    ],

];
