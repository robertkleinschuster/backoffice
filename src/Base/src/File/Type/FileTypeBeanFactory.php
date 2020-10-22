<?php


namespace Base\File\Type;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class FileTypeBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
       return new FileTypeBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new FileTypeBeanList();
    }

}
