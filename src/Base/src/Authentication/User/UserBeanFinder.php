<?php

namespace Pars\Base\Authentication\User;

use Pars\Base\Authorization\UserRole\UserRoleBeanFinder;
use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use Niceshops\Bean\Finder\AbstractBeanFinder;
use Pars\Mvc\Helper\ValidationHelperAwareInterface;
use Pars\Mvc\Helper\ValidationHelperAwareTrait;

/**
 * Class UserBeanFinder
 * @package Pars\Base\Authentication\User
 */
class UserBeanFinder extends AbstractBeanFinder implements
    UserRepositoryInterface,
    ValidationHelperAwareInterface,
    TranslatorAwareInterface
{
    use ValidationHelperAwareTrait;
    use TranslatorAwareTrait;

    /**
     * @var Adapter
     */
    private Adapter $adapter;

    /**
     * UserBeanFinder constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('Person_ID', 'Person_ID', 'Person', 'Person_ID', true, null, ['User']);
        $loader->addColumn('Person_Firstname', 'Person_Firstname', 'Person', 'Person_ID');
        $loader->addColumn('Person_Lastname', 'Person_Lastname', 'Person', 'Person_ID');
        $loader->addColumn('User_Username', 'User_Username', 'User', 'Person_ID');
        $loader->addColumn('User_Displayname', 'User_Displayname', 'User', 'Person_ID');
        $loader->addColumn('User_Password', 'User_Password', 'User', 'Person_ID');
        $loader->addColumn('Locale_Code', 'Locale_Code', 'User', 'Person_ID');
        $loader->addColumn('UserState_Code', 'UserState_Code', 'User', 'Person_ID');
        parent::__construct($loader, new UserBeanFactory());
        $userRoleFinder = new UserRoleBeanFinder($adapter);
        $userRoleFinder->setUserRole_Active(true);
        $this->addLinkedFinder($userRoleFinder, 'UserRole_BeanList', 'Person_ID', 'Person_ID');
    }

    /**
     * @param string $credential
     * @param string|null $password
     * @return UserInterface|null
     */
    public function authenticate(string $credential, string $password = null): ?UserInterface
    {
        $this->setUser_Username($credential);
        $this->setUserState_Code(UserBean::STATE_ACTIVE);
        if ($this->count() === 1) {
            $bean = $this->getBean(true);
            if (password_verify($password, $bean->getData('User_Password'))) {
                return $bean;
            } else {
                $this->getValidationHelper()->addError('User_Password', $this->translate('user.password.invalid'));
            }
        } else {
            $this->getValidationHelper()->addError('User_Username', $this->translate('user.username.invalid'));
        }
        return null;
    }

    /**
     * @param string $message
     * @return string
     */
    protected function translate(string $message)
    {
        if ($this->hasTranslator()) {
            return $this->getTranslator()->translate($message, 'validation');
        }
        return $message;
    }

    /**
     * @param int $person_id
     * @param bool $exclude
     * @return $this
     */
    public function setPerson_ID(int $person_id, bool $exclude = false): self
    {
        if ($exclude) {
            $this->getBeanLoader()->excludeValue('Person_ID', $person_id);
        } else {
            $this->getBeanLoader()->filterValue('Person_ID', $person_id);
        }
        return $this;
    }

    /**
     * @param string $user_username
     * @return $this
     */
    public function setUser_Username(string $user_username): self
    {
        $this->getBeanLoader()->filterValue('User_Username', $user_username);
        return $this;
    }


    /**
     * @param string $userState_Code
     * @return $this
     */
    public function setUserState_Code(string $userState_Code): self
    {
        $this->getBeanLoader()->filterValue('UserState_Code', $userState_Code);
        return $this;
    }
}
