<?php


namespace Base\File\Directory;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Mvc\Helper\ValidationHelperAwareInterface;
use Mvc\Helper\ValidationHelperAwareTrait;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class FileDirectoryBeanProcessor extends AbstractBeanProcessor implements ValidationHelperAwareInterface, TranslatorAwareInterface
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
        return !$this->getValidationHelper()->hasError();
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
        return !$this->getValidationHelper()->hasError();
    }

}
