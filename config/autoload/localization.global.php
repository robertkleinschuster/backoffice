<?php

use Base\Translation\TranslationLoader\TranslationBeanFinder;

return [
    'translator' => [
        'locale' => ['de_DE'],
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
                'text_domain' => 'default'
            ],
            [
                'type' => TranslationBeanFinder::class,
                'text_domain' => 'backoffice'
            ],
            [
                'type' => TranslationBeanFinder::class,
                'text_domain' => 'frontend'
            ]
        ],
        'cache' => [
            'adapter' => [
                'name'    => Laminas\Cache\Storage\Adapter\Filesystem::class,
                'options' => [
                    'cache_dir' => getcwd() . '/data/cache',
                ],
            ],
            'plugins' => [
                'serializer',
                'exception_handler' => [
                    'throw_exceptions' => false,
                ],
            ],
        ],
        'event_manager_enabled' => true
    ]
];
