<?php

namespace Pars\Backoffice\Mvc\User;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Authentication\User\UserBeanFinder;
use Pars\Base\Authentication\User\UserBeanProcessor;
use Pars\Base\Authentication\UserState\UserStateBeanFinder;
use Pars\Base\Localization\Locale\LocaleBeanFinder;

/**
 * Class UserModel
 * @package Pars\Backoffice\Mvc\Model
 * @method UserBeanFinder getBeanFinder() : BeanFinderInterface
 * @method UserBeanProcessor getProcessor() : BeanProcessorInterface
 */
class UserModel extends CrudModel
{

    public function initialize()
    {
        $this->setBeanFinder(new UserBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new UserBeanProcessor($this->getDbAdpater()));
    }


    /**
     * @return array
     */
    public function getUserState_Options(): array
    {
        $options = [];
        $finder = new UserStateBeanFinder($this->getDbAdpater());

        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('UserState_Code')] = $bean->getData('UserState_Code');
        }
        return $options;
    }

    /**
     * @return array
     */
    public function getLocale_Options(): array
    {
        $options = [];
        $finder = new LocaleBeanFinder($this->getDbAdpater());
        $finder->setLocale_Active(true);

        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('Locale_Code')] = $bean->getData('Locale_Name');
        }
        return $options;
    }
}
