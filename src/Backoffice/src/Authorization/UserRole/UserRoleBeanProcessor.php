<?php


namespace Backoffice\Authorization\UserRole;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Mvc\Helper\ValidationHelperAwareInterface;
use Mvc\Helper\ValidationHelperAwareTrait;
use NiceshopsDev\Bean\BeanInterface;

class UserRoleBeanProcessor extends \NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor implements ValidationHelperAwareInterface
{
    use ValidationHelperAwareTrait;

    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('UserRole_ID', 'UserRole_ID', 'User_UserRole', 'UserRole_ID', true);
        $saver->addColumn('Person_ID', 'Person_ID', 'User_UserRole', 'Person_ID', true);
        parent::__construct($saver);
    }


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
