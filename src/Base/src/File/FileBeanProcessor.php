<?php


namespace Base\File;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Mvc\Helper\ValidationHelperAwareInterface;
use Mvc\Helper\ValidationHelperAwareTrait;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class FileBeanProcessor extends AbstractBeanProcessor implements ValidationHelperAwareInterface, TranslatorAwareInterface
{
    use ValidationHelperAwareTrait;
    use TranslatorAwareTrait;

    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('File_ID', 'File_ID', 'File', 'File_ID', true);
        $saver->addColumn('File_Name', 'File_Name', 'File', 'File_ID');
        $saver->addColumn('FileType_Code', 'FileType_Code', 'File', 'File_ID');
        $saver->addColumn('FileDirectory_Code', 'FileDirectory_Code', 'File', 'File_ID');
        parent::__construct($saver);
    }

    protected function translate(string $name): string
    {
        return $this->getTranslator()->translate($name, 'validation');
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        if (!$bean->hasData('FileDirectory_Code') || !strlen(trim(($bean->getData('FileDirectory_Code'))))) {
            $this->getValidationHelper()->addError('FileDirectory_Code', $this->translate('filedirectory.code.empty'));
        }
        if (!$bean->hasData('FileType_Code') || !strlen(trim(($bean->getData('FileType_Code'))))) {
            $this->getValidationHelper()->addError('FileType_Code', $this->translate('filetype.code.empty'));
        }
        return parent::validateForSave($bean);
    }


}
