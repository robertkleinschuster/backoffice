<?php


namespace Backoffice\Authentication\Bean;


use Mezzio\Authentication\UserInterface;
use Mezzio\Mvc\View\ComponentDataBeanInterface;
use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class UserBean extends AbstractJsonSerializableBean implements ComponentDataBeanInterface, UserInterface
{

    public const STATE_ACTIVE = 'active';
    public const STATE_INACTIVE = 'inactive';
    public const STATE_LOCKED = 'locked';

    /**
     * UserBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('Person_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('Person_Firstname', self::DATA_TYPE_STRING, true);
        $this->setDataType('Person_Lastname', self::DATA_TYPE_STRING, true);
        $this->setDataType('User_Username', self::DATA_TYPE_STRING, true);
        $this->setDataType('User_Displayname', self::DATA_TYPE_STRING, true);
        $this->setDataType('User_Password', self::DATA_TYPE_STRING, true);
        $this->setDataType('UserState_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('Roles', self::DATA_TYPE_ARRAY, true);
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
