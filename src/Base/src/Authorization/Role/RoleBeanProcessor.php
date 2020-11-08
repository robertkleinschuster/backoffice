<?php

namespace Pars\Base\Authorization\Role;

use Pars\Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Niceshops\Bean\Processor\AbstractBeanProcessor;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Mvc\Helper\ValidationHelperAwareInterface;
use Pars\Mvc\Helper\ValidationHelperAwareTrait;

/**
 * Class RoleBeanProcessor
 * @package Pars\Base\Authorization\Role
 */
class RoleBeanProcessor extends AbstractBeanProcessor implements ValidationHelperAwareInterface, TranslatorAwareInterface
{
    use ValidationHelperAwareTrait;
    use TranslatorAwareTrait;

    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('UserRole_ID', 'UserRole_ID', 'UserRole', 'UserRole_ID', true);
        $saver->addColumn('UserRole_Code', 'UserRole_Code', 'UserRole', 'UserRole_ID');
        $saver->addColumn('UserRole_Active', 'UserRole_Active', 'UserRole', 'UserRole_ID');

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

    protected function validateForSave(BeanInterface $bean): bool
    {
        if (!$bean->hasData('UserRole_Code') || empty($bean->getData('UserRole_Code'))) {
            $this->getValidationHelper()->addError('UserRole_Code', $this->translate('userrole.code.empty'));
        }
        $finder = new RoleBeanFinder($this->adapter);
        $finder->setUserRole_Code($bean->getData('UserRole_Code'));
        if ($bean->hasData('UserRole_ID')) {
            $finder->setUserRole_ID($bean->getData('UserRole_ID'), true);
        }
        if ($finder->count() !== 0) {
            $this->getValidationHelper()->addError('UserRole_Code', $this->translate('userrole.code.unique'));
        }
        return !$this->getValidationHelper()->hasError();
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
        return true;
    }
}
