<?php

namespace Pars\Backoffice\Mvc\Role;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Authorization\Role\RoleBeanFinder;
use Pars\Base\Authorization\Role\RoleBeanProcessor;

/**
 * Class UserRoleModel
 * @package Pars\Backoffice\Mvc\Model
 * @method RoleBeanFinder getBeanFinder() : BeanFinderInterface
 * @method RoleBeanProcessor getProcessor() : BeanProcessorInterface
 */
class RoleModel extends CrudModel
{

    public function initialize()
    {
        $this->setBeanFinder(new RoleBeanFinder($this->adapter));
        $this->setBeanProcessor(new RoleBeanProcessor($this->adapter));
    }
}
