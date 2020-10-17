<?php


namespace Base\Localization\Locale;


use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class LocaleBeanFactory extends \NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory
{

    /**
     * @inheritDoc
     */
    public function createBean(): BeanInterface
    {
        return new LocaleBean();
    }

    /**
     * @inheritDoc
     */
    public function createBeanList(): BeanListInterface
    {
        return new LocaleBeanList();
    }
}
