<?php

return [
    'translator' => [
        'locale' => ['de_DE', 'en_US'],
        'translation_file_patterns' => [
            [
                'type'     => Laminas\I18n\Translator\Loader\PhpArray::class,
                'base_dir' => __DIR__ . '/../../data/translation',
                'pattern'  => '%s.php',
                'text_domain' => 'default'

            ],
        ],
        'translation_files' => [
            [
                'type' => Laminas\I18n\Translator\Loader\PhpArray::class,
                'filename' => __DIR__ . '/../../data/translation/de_AT.php',
                'text_domain' => 'default',
                'locale' => 'de_AT'
            ]
        ],
        'remote_translation' => [
            [
                'type' => '',
                'text_domain' => '',
            ]
        ],
       /* 'cache' => [
            'adapter' => [
                'name'    => Laminas\Cache\Storage\Adapter\Memcached::class,
                'options' => [
                    'servers' => [
                        ['localhost', 11211]
                    ],
                ],
            ],
        ],*/
        'event_manager_enabled' => true
    ]
];
