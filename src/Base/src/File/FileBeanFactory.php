<?php

namespace Pars\Base\File;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class FileBeanFactory
 * @package Pars\Base\File
 */
class FileBeanFactory extends AbstractBeanFactory
{
    protected function getBeanClass(array $data): string
    {
        return FileBean::class;
    }

    protected function getBeanListClass(): string
    {
        return FileBeanList::class;
    }
}
