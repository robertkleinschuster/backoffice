<?php


namespace Backoffice\Mvc\User;

use Base\Authentication\User\UserBeanFinder;
use Base\Authentication\User\UserBeanProcessor;
use Backoffice\Mvc\Base\BaseModel;
use Base\Authentication\UserState\UserStateBeanFinder;

/**
 * Class UserModel
 * @package Backoffice\Mvc\Model
 * @method UserBeanFinder getFinder() : BeanFinderInterface
 * @method UserBeanProcessor getProcessor() : BeanProcessorInterface
 */
class UserModel extends BaseModel
{

    public function init()
    {
        $this->setFinder(new UserBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new UserBeanProcessor($this->getDbAdpater()));
    }


    /**
     * @return array
     */
    public function getUserState_Options(): array {
        $options = [];
        $finder = new UserStateBeanFinder($this->getDbAdpater());
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('UserState_Code')] = $bean->getData('UserState_Code');
        }
        return $options;
    }
}
