<?php

declare(strict_types=1);

use Pars\Base\Database\DatabaseMiddlewareFactory;
use Pars\Base\Authentication\AuthenticationMiddleware;
use Pars\Base\Authentication\AuthenticationMiddlewareFactory;
use Pars\Base\Authentication\User\UserBeanFactory;
use Pars\Base\Authentication\UserRepositoryFactory;
use Pars\Base\Database\DatabaseMiddleware;
use Pars\Base\Localization\LocalizationMiddleware;
use Pars\Base\Localization\LocalizationMiddlewareFactory;
use Pars\Base\Logging\LoggingErrorListenerDelegatorFactory;
use Pars\Base\Logging\LoggingMiddleware;
use Pars\Base\Logging\LoggingMiddlewareFactory;
use Pars\Base\Session\Cache\FilesystemCachePoolFactory;
use Pars\Base\Translation\TranslatorMiddleware;
use Pars\Base\Translation\TranslatorMiddlewareFactory;
use Laminas\Stratigility\Middleware\ErrorHandler;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Session\Cache\CacheSessionPersistence;
use Mezzio\Session\SessionPersistenceInterface;

return [
    'dependencies' => [
        'aliases' => [
            SessionPersistenceInterface::class => CacheSessionPersistence::class,
            AuthenticationInterface::class => PhpSession::class,
        ],
        'invokables' => [
        ],
        'factories' => [
            'SessionCache' => FilesystemCachePoolFactory::class,
            AuthenticationMiddleware::class => AuthenticationMiddlewareFactory::class,
            UserRepositoryInterface::class => UserRepositoryFactory::class,
            UserInterface::class => UserBeanFactory::class,
            DatabaseMiddleware::class => DatabaseMiddlewareFactory::class,
            TranslatorMiddleware::class => TranslatorMiddlewareFactory::class,
            LoggingMiddleware::class => LoggingMiddlewareFactory::class,
            LocalizationMiddleware::class => LocalizationMiddlewareFactory::class,
        ],
        'delegators' => [
            ErrorHandler::class => [
                LoggingErrorListenerDelegatorFactory::class,
            ],
        ],
    ],
];
