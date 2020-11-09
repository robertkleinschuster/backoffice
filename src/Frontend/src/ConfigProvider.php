<?php

declare(strict_types=1);

namespace Pars\Frontend;

use Pars\Frontend\Handler\Cms\CmsHandler;
use Pars\Frontend\Handler\Cms\CmsHandlerFactory;

/**
 * The configuration provider for the Frontend module
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
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
            ],
            'factories'  => [
                CmsHandler::class => CmsHandlerFactory::class
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
                'cmserror'    => [__DIR__ . '/../templates/error'],
                'CmsPage'    => [__DIR__ . '/../templates/site'],
                'cmsmenu'    => [__DIR__ . '/../templates/menu'],
                'cmsparagraph'    => [__DIR__ . '/../templates/paragraph'],
                'cmspost'    => [__DIR__ . '/../templates/post'],
            ],
        ];
    }
}
