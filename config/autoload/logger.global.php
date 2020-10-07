<?php

declare(strict_types=1);

use Laminas\Log\Formatter\Simple;
use Laminas\Log\Logger;
use Laminas\Log\Processor\RequestId;

return [
    'psr_log' => [
        'Logger' => [
            'exceptionhandler' => true,
            'errorhandler' => true,
            'fatal_error_shutdownfunction' => true,
            'writers' => [
                'syslog' => [
                    'name' => 'syslog',
                    'priority' => 1,
                    'options' => [

                        'application' => 'backoffice',
                        'facility' => LOG_LOCAL0,
                        'formatter' => [
                            'name' => Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                                'dateTimeFormat' => 'c',
                            ],
                        ],
                        'filters' => [
                            'priority' => [
                                'name' => 'priority',
                                'options' => [
                                    'operator' => '<=',
                                    'priority' => Logger::INFO,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'processors' => [
                'requestid' => [
                    'name' => RequestId::class,
                ],
            ],
        ],
    ],
];
