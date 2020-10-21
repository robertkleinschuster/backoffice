<?php


namespace Base\Cms\Paragraph\State;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsParagraphStateBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new CmsParagraphStateBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsParagraphStateBeanList();
    }

}
