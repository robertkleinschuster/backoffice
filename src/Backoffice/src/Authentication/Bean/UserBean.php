<?php


namespace Backoffice\Authentication\Bean;


use Mezzio\Authentication\UserInterface;
use Mezzio\Mvc\View\ComponentDataBeanInterface;
use NiceshopsDev\Bean\Database\AbstractDatabaseBean;

class UserBean extends AbstractDatabaseBean implements ComponentDataBeanInterface, UserInterface
{

    public const STATE_ACTIVE = 'active';
    public const STATE_INACTIVE = 'inactive';
    public const STATE_LOCKED = 'locked';

    /**
     * UserBean constructor.
     */
    public function __construct()
    {
        $this->setDatabaseField('Person_ID', self::DATA_TYPE_INT, [self::COLUMN_TYPE_PRIMARY_KEY, self::COLUMN_TYPE_FOREIGN_KEY]);
        $this->setDatabaseField('Person_Firstname', self::DATA_TYPE_STRING);
        $this->setDatabaseField('Person_Lastname', self::DATA_TYPE_STRING);
        $this->setDatabaseField('User_Username', self::DATA_TYPE_STRING);
        $this->setDatabaseField('User_Displayname', self::DATA_TYPE_STRING);
        $this->setDatabaseField('User_Password', self::DATA_TYPE_STRING);
        $this->setDatabaseField('UserState_Code', self::DATA_TYPE_STRING, [self::COLUMN_TYPE_FOREIGN_KEY]);
        $this->setDataType('Roles', self::DATA_TYPE_ARRAY);
    }

    public function getIdentity(): string
    {
        return $this->getData('User_Username');
    }

    public function getRoles(): iterable
    {
        return $this->getData('Roles');
    }

    public function getDetail(string $name, $default = null)
    {
        return $this->hasData($name) ? $this->getData($name) : $default;
    }

    public function getDetails(): array
    {
        return $this->toArray();
    }
}
