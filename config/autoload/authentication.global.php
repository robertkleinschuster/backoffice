<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\Log\Formatter\Simple;
use Laminas\Log\Logger;
use Laminas\Log\Processor\RequestId;

return [
    'authentication' => [
        'redirect' => [
            'controller' => 'auth',
            'action' => 'login'
        ],
        'whitelist' => [
            [
                'controller' => 'auth',
                'action' => 'login'
            ],
            [
                'controller' => 'setup',
                'action' => 'index'
            ]
        ],
        'username' => 'login_username',
        'password' => 'login_password',
        'module' => []
    ],
];
