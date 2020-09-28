<?php

declare(strict_types=1);

namespace Backoffice;

use Backoffice\Mvc\Controller\AuthenticationController;
use Backoffice\Mvc\Controller\IndexController;
use Backoffice\Mvc\Controller\UserController;
use Backoffice\Mvc\Model\AuthenticationModel;
use Backoffice\Mvc\Model\IndexModel;
use Backoffice\Mvc\Model\UserModel;
use Laminas\ServiceManager\Factory\InvokableFactory;

/**
 * The configuration provider for the Backoffice module
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
            'db' => [
                'driver'   => 'Pdo_Sqlite',
                'database' => __DIR__ . '/../../../data/sqlite.db',
            ],
            'mvc' => [
                'controllers' => [
                    'index' => IndexController::class,
                    'auth' => AuthenticationController::class,
                    'user' => UserController::class,
                ],
                'models' => [
                    'index' => IndexModel::class,
                    'auth' => AuthenticationModel::class,
                    'user' => UserModel::class,
                ],
            ]
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
            ],
            'factories'  => [
                IndexController::class => InvokableFactory::class,
                IndexModel::class => InvokableFactory::class,
                AuthenticationModel::class => InvokableFactory::class,
                AuthenticationController::class => InvokableFactory::class,
                UserController::class => InvokableFactory::class,
                UserModel::class => InvokableFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
                'mvc'    => [__DIR__ . '/../templates/mvc'],
            ],
        ];
    }
}
