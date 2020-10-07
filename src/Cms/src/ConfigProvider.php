<?php

declare(strict_types=1);

namespace Cms;

use Cms\Mvc\Index\IndexController;
use Cms\Mvc\Index\IndexModel;

/**
 * The configuration provider for the Cms module
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
            'mvc' => $this->getMvc()
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
            ],
        ];
    }

    public function getMvc(): array
    {
        return [
            'module' => [
                'cms' => [
                    'template_folder' => 'cms',
                    'controllers' => [
                        'index' => IndexController::class
                    ],
                    'models' => [
                        'index' => IndexModel::class
                    ],
                ]
            ]
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'cms'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
