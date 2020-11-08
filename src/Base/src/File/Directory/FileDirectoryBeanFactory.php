<?php

namespace Pars\Base\File\Directory;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class FileDirectoryBeanFactory
 * @package Pars\Base\File\Directory
 */
class FileDirectoryBeanFactory extends AbstractBeanFactory
{
    protected function getBeanClass(array $data): string
    {
        return FileDirectoryBean::class;
    }

    protected function getBeanListClass(): string
    {
        return FileDirectoryBeanList::class;
    }
}
