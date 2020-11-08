<?php

declare(strict_types=1);

namespace Pars\Backoffice;

use Pars\Backoffice\Mvc\Authentication\AuthenticationController;
use Pars\Backoffice\Mvc\Authentication\AuthenticationModel;
use Pars\Backoffice\Mvc\Cms\Menu\CmsMenuController;
use Pars\Backoffice\Mvc\Cms\Menu\CmsMenuModel;
use Pars\Backoffice\Mvc\Cms\Paragraph\CmsParagraphController;
use Pars\Backoffice\Mvc\Cms\Paragraph\CmsParagraphModel;
use Pars\Backoffice\Mvc\Cms\Site\CmsSiteController;
use Pars\Backoffice\Mvc\Cms\Site\CmsSiteModel;
use Pars\Backoffice\Mvc\Cms\SiteParagraph\CmsSiteParagraphController;
use Pars\Backoffice\Mvc\Cms\SiteParagraph\CmsSiteParagraphModel;
use Pars\Backoffice\Mvc\File\Directory\FileDirectoryController;
use Pars\Backoffice\Mvc\File\Directory\FileDirectoryModel;
use Pars\Backoffice\Mvc\File\FileController;
use Pars\Backoffice\Mvc\File\FileModel;
use Pars\Backoffice\Mvc\Index\IndexController;
use Pars\Backoffice\Mvc\Index\IndexModel;
use Pars\Backoffice\Mvc\Locale\LocaleController;
use Pars\Backoffice\Mvc\Locale\LocaleModel;
use Pars\Backoffice\Mvc\Role\RoleController;
use Pars\Backoffice\Mvc\Role\RoleModel;
use Pars\Backoffice\Mvc\RolePermission\RolePermissionController;
use Pars\Backoffice\Mvc\RolePermission\RolePermissionModel;
use Pars\Backoffice\Mvc\Setup\SetupController;
use Pars\Backoffice\Mvc\Setup\SetupModel;
use Pars\Backoffice\Mvc\Translation\TranslationController;
use Pars\Backoffice\Mvc\Translation\TranslationModel;
use Pars\Backoffice\Mvc\Update\UpdateController;
use Pars\Backoffice\Mvc\Update\UpdateModel;
use Pars\Backoffice\Mvc\User\UserController;
use Pars\Backoffice\Mvc\User\UserModel;
use Pars\Backoffice\Mvc\UserRole\UserRoleController;
use Pars\Backoffice\Mvc\UserRole\UserRoleModel;

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
                    'error_controller' => 'index',
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
                        'locale' => LocaleController::class,
                        'cmsmenu' => CmsMenuController::class,
                        'cmssite' => CmsSiteController::class,
                        'cmsparagraph' => CmsParagraphController::class,
                        'cmssiteparagraph' => CmsSiteParagraphController::class,
                        'filedirectory' => FileDirectoryController::class,
                        'file' => FileController::class,
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
                        'locale' => LocaleModel::class,
                        'cmsmenu' => CmsMenuModel::class,
                        'cmssite' => CmsSiteModel::class,
                        'cmsparagraph' => CmsParagraphModel::class,
                        'cmssiteparagraph' => CmsSiteParagraphModel::class,
                        'filedirectory' => FileDirectoryModel::class,
                        'file' => FileModel::class,
                    ],
                ]
            ]
        ];
    }
}
