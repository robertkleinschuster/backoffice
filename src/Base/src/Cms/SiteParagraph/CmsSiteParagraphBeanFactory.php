<?php


namespace Base\Cms\SiteParagraph;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsSiteParagraphBeanFactory extends AbstractBeanFactory
{
    /**
     * @return BeanInterface
     */
    public function createBean(): BeanInterface
    {
       return new CmsSiteParagraphBean();
    }

    /**
     * @return BeanListInterface
     */
    public function createBeanList(): BeanListInterface
    {
        return new CmsSiteParagraphBeanList();
    }

}
