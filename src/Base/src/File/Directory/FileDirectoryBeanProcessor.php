<?php

namespace Pars\Base\File\Directory;

use Pars\Base\Database\DatabaseBeanSaver;
use Cocur\Slugify\Slugify;
use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

use Niceshops\Bean\Processor\AbstractBeanProcessor;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Mvc\Helper\ValidationHelperAwareInterface;
use Pars\Mvc\Helper\ValidationHelperAwareTrait;

/**
 * Class FileDirectoryBeanProcessor
 * @package Pars\Base\File\Directory
 */
class FileDirectoryBeanProcessor extends AbstractBeanProcessor implements
    ValidationHelperAwareInterface,
    TranslatorAwareInterface
{
    use ValidationHelperAwareTrait;
    use TranslatorAwareTrait;

    /**
     * FileDirectoryBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('FileDirectory_ID', 'FileDirectory_ID', 'FileDirectory', 'FileDirectory_ID', true);
        $saver->addColumn('FileDirectory_Code', 'FileDirectory_Code', 'FileDirectory', 'FileDirectory_ID');
        $saver->addColumn('FileDirectory_Name', 'FileDirectory_Name', 'FileDirectory', 'FileDirectory_ID');
        $saver->addColumn('FileDirectory_Active', 'FileDirectory_Active', 'FileDirectory', 'FileDirectory_ID');
        parent::__construct($saver);
    }

    protected function translate(string $name): string
    {
        return $this->getTranslator()->translate($name, 'validation');
    }

    /**
     * @param BeanInterface $bean
     * @return Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        $path = implode(DIRECTORY_SEPARATOR, [
            $_SERVER["DOCUMENT_ROOT"], 'upload'
        ]);
        $filesystemAdapter = new Local($path);
        return new Filesystem($filesystemAdapter);
    }

    protected function beforeSave(BeanInterface $bean)
    {
        parent::beforeSave($bean);
        $filesystem = $this->getFilesystem();
        if (!$bean->hasData('FileDirectory_Code')) {
            $slugify = new Slugify();
            $bean->setData('FileDirectory_Code', $slugify->slugify($bean->getData('FileDirectory_Name')));
        }
        if (!$filesystem->has($bean->getData('FileDirectory_Code'))) {
            $filesystem->createDir($bean->getData('FileDirectory_Code'));
        }
    }

    public function delete(): int
    {
        $filesystem = $this->getFilesystem();
        $beanList = $this->getBeanListForDelete();
        foreach ($beanList as $bean) {
            if ($filesystem->has($bean->getData('FileDirectory_Code'))) {
                $filesystem->deleteDir($bean->getData('FileDirectory_Code'));
            }
        }
        return parent::delete();
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        if (!$bean->hasData('FileDirectory_Name') || !strlen(trim(($bean->getData('FileDirectory_Name'))))) {
            $this->getValidationHelper()->addError('FileDirectory_Name', $this->translate('filedirectory.name.empty'));
        }
        return !$this->getValidationHelper()->hasError();
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
        return !$this->getValidationHelper()->hasError();
    }
}
