<?php

use Base\Translation\TranslationLoader\TranslationBeanFinder;

return [
    'translator' => [
        'locale' => ['de_DE', 'de_DE'],
        'translation_file_patterns' => [
            [
                'type'     => Laminas\I18n\Translator\Loader\PhpArray::class,
                'base_dir' => __DIR__ . '/../../data/translation/default',
                'pattern'  => '%s.php',
                'text_domain' => 'default'

            ],
            [
                'type'     => Laminas\I18n\Translator\Loader\PhpArray::class,
                'base_dir' => __DIR__ . '/../../data/translation/backoffice',
                'pattern'  => '%s.php',
                'text_domain' => 'backoffice'

            ],
            [
                'type'     => Laminas\I18n\Translator\Loader\PhpArray::class,
                'base_dir' => __DIR__ . '/../../data/translation/validation',
                'pattern'  => '%s.php',
                'text_domain' => 'validation'

            ],
        ],
        'translation_files' => [
        ],
        'remote_translation' => [
            [
                'type' => TranslationBeanFinder::class,
            ]
        ],
        'cache' => [
            'adapter' => [
                'name'    => Laminas\Cache\Storage\Adapter\Memcached::class,
                'options' => [
                    'servers' => [
                        ['localhost', 11211]
                    ],
                ],
            ],
        ],
        'event_manager_enabled' => true
    ]
];
