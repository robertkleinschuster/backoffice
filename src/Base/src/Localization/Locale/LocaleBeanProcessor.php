<?php


namespace Base\Localization\Locale;


use NiceshopsDev\Bean\BeanInterface;

class LocaleBeanProcessor extends \NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor
{

    /**
     * @inheritDoc
     */
    protected function validateForSave(BeanInterface $bean): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function validateForDelete(BeanInterface $bean): bool
    {
        return true;
    }

}
