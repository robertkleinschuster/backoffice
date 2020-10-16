<?php

declare(strict_types=1);

namespace Backoffice;

use Backoffice\Mvc\Authentication\AuthenticationController;
use Backoffice\Mvc\Authentication\AuthenticationModel;
use Backoffice\Mvc\Index\IndexController;
use Backoffice\Mvc\Index\IndexModel;
use Backoffice\Mvc\Role\RoleController;
use Backoffice\Mvc\Role\RoleModel;
use Backoffice\Mvc\RolePermission\RolePermissionController;
use Backoffice\Mvc\RolePermission\RolePermissionModel;
use Backoffice\Mvc\Setup\SetupController;
use Backoffice\Mvc\Setup\SetupModel;
use Backoffice\Mvc\Translation\TranslationController;
use Backoffice\Mvc\Translation\TranslationModel;
use Backoffice\Mvc\Update\UpdateController;
use Backoffice\Mvc\Update\UpdateModel;
use Backoffice\Mvc\User\UserController;
use Backoffice\Mvc\User\UserModel;
use Backoffice\Mvc\UserRole\UserRoleController;
use Backoffice\Mvc\UserRole\UserRoleModel;



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
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates' => $this->getTemplates(),
            'mvc' => $this->getMvc(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'aliases' => [
            ],
            'invokables' => [
            ],
            'factories' => [
            ],
            'delegators' => [
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'mvc' => [__DIR__ . '/../templates/mvc'],
            ],
        ];
    }

    public function getMvc(): array
    {
        return [
            'module' => [
                'backoffice' => [
                    'controllers' => [
                        'setup' => SetupController::class,
                        'index' => IndexController::class,
                        'auth' => AuthenticationController::class,
                        'user' => UserController::class,
                        'update' => UpdateController::class,
                        'role' => RoleController::class,
                        'rolepermission' => RolePermissionController::class,
                        'userrole' => UserRoleController::class,
                        'translation' => TranslationController::class,
                    ],
                    'models' => [
                        'setup' => SetupModel::class,
                        'index' => IndexModel::class,
                        'auth' => AuthenticationModel::class,
                        'user' => UserModel::class,
                        'update' => UpdateModel::class,
                        'role' => RoleModel::class,
                        'rolepermission' => RolePermissionModel::class,
                        'userrole' => UserRoleModel::class,
                        'translation' => TranslationModel::class,
                    ],
                ]
            ]
        ];
    }
}
