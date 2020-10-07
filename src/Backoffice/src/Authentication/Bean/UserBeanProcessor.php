<?php


namespace Backoffice\Authentication\Bean;


use Backoffice\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Mvc\Helper\ValidationHelper;
use Mvc\Helper\ValidationHelperAwareInterface;
use Mvc\Helper\ValidationHelperAwareTrait;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class UserBeanProcessor extends AbstractBeanProcessor implements ValidationHelperAwareInterface
{
    use ValidationHelperAwareTrait;


    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * UserBeanProcessor constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $saver = new DatabaseBeanSaver($adapter, 'Person', 'User');
        $saver->setFieldColumnMap([
            'Person_ID' => 'Person_ID',
            'Person_Firstname' => 'Person_Firstname',
            'Person_Lastname' => 'Person_Lastname',
            'User_Username' => 'User_Username',
            'User_Displayname' => 'User_Displayname',
            'User_Password' => 'User_Password',
            'UserState_Code' => 'UserState_Code',
        ]);
        $saver->setPrimaryKeyList([
            'Person_ID'
        ]);
        parent::__construct($saver);

    }

    /**
     * @param BeanInterface $bean
     * @return bool
     * @throws \NiceshopsDev\Bean\BeanException
     */
    protected function validateForSave(BeanInterface $bean): bool
    {
        if ($bean->hasData('User_Username') && strlen(trim($bean->getData('User_Username')))) {
            $finder = new UserBeanFinder($this->adapter);
            $finder->getLoader()->initByIdMap(['User_Username' => $bean->getData('User_Username')]);
            $count = $finder->count();
            if ($count) {
                $finder->find();
                $foundBean = $finder->getBean();
                if (!$bean->hasData('Person_ID') || $bean->getData('Person_ID') != $foundBean->getData('Person_ID')) {
                    $this->getValidationHelper()->addError('User_Username', 'Der Benutzername ist bereits vergeben.');
                }
            }
        } else {
            $this->getValidationHelper()->addError('User_Username', 'Der Benutzername darf nicht leer sein.');
        }
        if (!$bean->hasData('User_Displayname') || !strlen(trim($bean->getData('User_Displayname')))) {
            $this->getValidationHelper()->addError('User_Displayname', 'Der Anzeigename darf nicht leer sein.');
        }
        if (!$bean->hasData('Person_Firstname') || !strlen(trim($bean->getData('Person_Firstname')))) {
            $this->getValidationHelper()->addError('Person_Firstname', 'Der Vorname darf nicht leer sein.');
        }
        if (!$bean->hasData('Person_Lastname') || !strlen(trim($bean->getData('Person_Lastname')))) {
            $this->getValidationHelper()->addError('Person_Lastname', 'Der Nachname darf nicht leer sein.');
        }
        if ($bean->hasData('User_Password') && $bean->getData('User_Password') == '') {
            $bean->removeData("User_Password");
        }
        if ($bean->hasData('User_Password')) {
            if (strlen($bean->getData('User_Password')) < 5) {
                $this->getValidationHelper()->addError('User_Password','Das Passwort muss länger als 5 Zeichen sein.');
            }
        } elseif (!$bean->hasData('Person_ID')) {
            $this->getValidationHelper()->addError('User_Password', 'Das Passwort darf nicht leer sein.');
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
        if ($finder->find() == 1) {
            return false;
        }
        return $bean->hasData('Person_ID');
    }

    /**
     * @param BeanInterface $bean
     */
    public function beforeSave(BeanInterface $bean) {
        if ($bean->hasData('User_Password')) {
            $password = $bean->getData('User_Password');
            $info = password_get_info($password);
            if ($info['algo'] === 0 ) {
                $bean->setData('User_Password', password_hash($password, PASSWORD_BCRYPT));
            }
        }
    }

}
