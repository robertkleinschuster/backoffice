<?php

declare(strict_types=1);

use Base\Authentication\AuthenticationMiddleware;
use Base\Authentication\AuthenticationMiddlewareFactory;
use Base\Authentication\Bean\UserBeanFactory;
use Base\Authentication\UserRepositoryFactory;
use Base\Database\DatabaseMiddleware;
use Base\Database\DatabaseMiddlewareFactory;
use Base\Logging\LoggingErrorListenerDelegatorFactory;
use Base\Session\Cache\MemcachedCachePoolFactory;
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
            'SessionCache' => MemcachedCachePoolFactory::class,
            AuthenticationMiddleware::class => AuthenticationMiddlewareFactory::class,
            UserRepositoryInterface::class => UserRepositoryFactory::class,
            UserInterface::class => UserBeanFactory::class,
            DatabaseMiddleware::class => DatabaseMiddlewareFactory::class,
        ],
        'delegators' => [
            ErrorHandler::class => [
                LoggingErrorListenerDelegatorFactory::class,
            ],
        ],
    ],
];
