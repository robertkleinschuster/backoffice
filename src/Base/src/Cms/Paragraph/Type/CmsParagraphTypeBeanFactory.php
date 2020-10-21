<?php


namespace Base\Cms\Paragraph\Type;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsParagraphTypeBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
       return new CmsParagraphTypeBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsParagraphTypeBeanList();
    }

}
