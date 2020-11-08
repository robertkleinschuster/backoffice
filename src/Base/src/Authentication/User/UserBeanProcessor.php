<?php

namespace Pars\Base\Authentication\User;

use Pars\Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Niceshops\Bean\Processor\AbstractBeanProcessor;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Mvc\Helper\ValidationHelperAwareInterface;
use Pars\Mvc\Helper\ValidationHelperAwareTrait;

class UserBeanProcessor extends AbstractBeanProcessor implements ValidationHelperAwareInterface, TranslatorAwareInterface
{
    use ValidationHelperAwareTrait;
    use TranslatorAwareTrait;

    /**
     * @var Adapter
     */
    private Adapter $adapter;

    /**
     * UserBeanProcessor constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('Person_ID', 'Person_ID', 'Person', 'Person_ID', true, null, ['User']);
        $saver->addColumn('Person_Firstname', 'Person_Firstname', 'Person', 'Person_ID');
        $saver->addColumn('Person_Lastname', 'Person_Lastname', 'Person', 'Person_ID');
        $saver->addColumn('User_Username', 'User_Username', 'User', 'Person_ID');
        $saver->addColumn('User_Displayname', 'User_Displayname', 'User', 'Person_ID');
        $saver->addColumn('User_Password', 'User_Password', 'User', 'Person_ID');
        $saver->addColumn('Locale_Code', 'Locale_Code', 'User', 'Person_ID');
        $saver->addColumn('UserState_Code', 'UserState_Code', 'User', 'Person_ID');
        parent::__construct($saver);
    }

    /**
     * @param string $code
     * @return string
     */
    protected function translate(string $code)
    {
        if ($this->hasTranslator()) {
            return $this->getTranslator()->translate($code, 'validation');
        }
        return $code;
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     */
    protected function validateForSave(BeanInterface $bean): bool
    {
        if ($bean->hasData('User_Username') && strlen(trim($bean->getData('User_Username')))) {
            $finder = new UserBeanFinder($this->adapter);
            $finder->setUser_Username($bean->getData('User_Username'));
            if ($bean->hasData('Person_ID')) {
                $finder->setPerson_ID($bean->getData('Person_ID'), true);
            }
            if ($finder->count() !== 0) {
                $this->getValidationHelper()->addError('User_Username', $this->translate('user.username.unique'));
            }
        } else {
            $this->getValidationHelper()->addError('User_Username', $this->translate('user.username.empty'));
        }
        if (!$bean->hasData('User_Displayname') || !strlen(trim($bean->getData('User_Displayname')))) {
            $this->getValidationHelper()->addError('User_Displayname', $this->translate('user.displayname.empty'));
        }
        if (!$bean->hasData('Person_Firstname') || !strlen(trim($bean->getData('Person_Firstname')))) {
            $this->getValidationHelper()->addError('Person_Firstname', $this->translate('person.firstname.empty'));
        }
        if (!$bean->hasData('Person_Lastname') || !strlen(trim($bean->getData('Person_Lastname')))) {
            $this->getValidationHelper()->addError('Person_Lastname', $this->translate('person.lastname.empty'));
        }
        if ($bean->hasData('User_Password') && $bean->getData('User_Password') == '') {
            $bean->removeData("User_Password");
        }
        if ($bean->hasData('User_Password')) {
            if (strlen($bean->getData('User_Password')) < 5) {
                $this->getValidationHelper()->addError('User_Password', $this->translate('user.password.min_length'));
            }
        } elseif (!$bean->hasData('Person_ID')) {
            $this->getValidationHelper()->addError('User_Password', $this->translate('user.password.empty'));
        }
        if ($bean->hasData('Person_ID')) {
            if ($bean->getData('UserState_Code') !== UserBean::STATE_ACTIVE && $bean->getData('Person_ID') == $this->getBeanSaver()->getPersonId()) {
                $this->getValidationHelper()->addError('UserState_Code', $this->translate('userstate.code.lock_self'));
            }
        }

        return !$this->getValidationHelper()->hasError();
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     */
    protected function validateForDelete(BeanInterface $bean): bool
    {
        $finder = new UserBeanFinder($this->adapter);
        if ($finder->count() == 1) {
            return false;
        }
        return $bean->hasData('Person_ID');
    }

    /**
     * @param BeanInterface $bean
     */
    public function beforeSave(BeanInterface $bean)
    {
        if ($bean->hasData('User_Password')) {
            $password = $bean->getData('User_Password');
            $info = password_get_info($password);
            if ($info['algo'] !== PASSWORD_BCRYPT) {
                $bean->setData('User_Password', password_hash($password, PASSWORD_BCRYPT));
            }
        }
    }
}
