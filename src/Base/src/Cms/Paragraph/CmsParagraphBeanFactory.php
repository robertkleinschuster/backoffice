<?php


namespace Base\Cms\Paragraph;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsParagraphBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new CmsParagraphBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsParagraphBeanList();
    }

}
