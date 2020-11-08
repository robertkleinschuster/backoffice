<?php

namespace Pars\Base\File\Type;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class FileTypeBeanFactory
 * @package Pars\Base\File\Type
 */
class FileTypeBeanFactory extends AbstractBeanFactory
{
    protected function getBeanClass(array $data): string
    {
        return FileTypeBean::class;
    }

    protected function getBeanListClass(): string
    {
        return FileTypeBeanList::class;
    }
}
