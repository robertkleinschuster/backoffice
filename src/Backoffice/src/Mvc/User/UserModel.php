<?php


namespace Backoffice\Mvc\User;

use Backoffice\Authentication\Bean\UserBeanFinder;
use Backoffice\Authentication\Bean\UserBeanProcessor;
use Backoffice\Mvc\Base\BaseModel;

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

}
