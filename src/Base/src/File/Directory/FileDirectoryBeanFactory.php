<?php


namespace Base\File\Directory;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class FileDirectoryBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new FileDirectoryBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new FileDirectoryBeanList();
    }

}
