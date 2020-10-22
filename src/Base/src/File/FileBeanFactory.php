<?php


namespace Base\File;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class FileBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new FileBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new FileBeanList();
    }

}
