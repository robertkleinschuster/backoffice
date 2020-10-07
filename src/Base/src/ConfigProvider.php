<?php

declare(strict_types=1);

namespace Base;

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

/**
 * The configuration provider for the Base module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [

        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'error' => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
                'base'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
