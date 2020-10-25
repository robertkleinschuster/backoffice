<?php

declare(strict_types=1);

namespace Backoffice;

use Backoffice\Mvc\Authentication\AuthenticationController;
use Backoffice\Mvc\Authentication\AuthenticationModel;
use Backoffice\Mvc\Cms\Menu\CmsMenuController;
use Backoffice\Mvc\Cms\Menu\CmsMenuModel;
use Backoffice\Mvc\Cms\Paragraph\CmsParagraphController;
use Backoffice\Mvc\Cms\Paragraph\CmsParagraphModel;
use Backoffice\Mvc\Cms\Site\CmsSiteController;
use Backoffice\Mvc\Cms\Site\CmsSiteModel;
use Backoffice\Mvc\Cms\SiteParagraph\CmsSiteParagraphController;
use Backoffice\Mvc\Cms\SiteParagraph\CmsSiteParagraphModel;
use Backoffice\Mvc\File\Directory\FileDirectoryController;
use Backoffice\Mvc\File\Directory\FileDirectoryModel;
use Backoffice\Mvc\File\FileController;
use Backoffice\Mvc\File\FileModel;
use Backoffice\Mvc\Index\IndexController;
use Backoffice\Mvc\Index\IndexModel;
use Backoffice\Mvc\Locale\LocaleController;
use Backoffice\Mvc\Locale\LocaleModel;
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
