<?php


namespace Backoffice\Mvc\Model;

use Backoffice\Authentication\Bean\UserBeanFinder;
use Backoffice\Authentication\Bean\UserBeanProcessor;


class UserModel extends BaseModel
{

    public function init()
    {
        $this->setFinder(new UserBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new UserBeanProcessor($this->getDbAdpater()));
    }

}
