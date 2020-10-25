<?php


namespace Base\File;


use Base\Database\DatabaseBeanSaver;
use Base\File\Directory\FileDirectoryBeanFinder;
use Base\File\Type\FileTypeBeanFinder;
use Cocur\Slugify\Slugify;
use Laminas\Db\Adapter\Adapter;
use Laminas\Diactoros\UploadedFile;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Mvc\Helper\ValidationHelperAwareInterface;
use Mvc\Helper\ValidationHelperAwareTrait;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;
use phpDocumentor\Reflection\File;

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
        $saver->addColumn('File_Code', 'File_Code', 'File', 'File_ID');
        $saver->addColumn('FileType_Code', 'FileType_Code', 'File', 'File_ID');
        $saver->addColumn('FileDirectory_ID', 'FileDirectory_ID', 'File', 'File_ID');
        parent::__construct($saver);
    }

    protected function beforeSave(BeanInterface $bean)
    {
        $filesystem = $this->getFilesystem($bean);
        $slugify = new Slugify();
        if (!$bean->hasData('File_Code')) {
            $bean->setData('File_Code', $slugify->slugify($bean->getData('File_Name')));
            $bean->setData('File_Code', "{$bean->getData('File_Code')}.{$bean->getData('FileType_Code')}");
        }


        if (!$filesystem->has($bean->getData('File_Code')) && $bean->hasData('Upload')) {
            $upload = $bean->getData('Upload');
            if ($upload instanceof UploadedFile) {
                $path = $this->getFilePath($bean);
                $upload->moveTo($path);
               /* $mime = $filesystem->getMimetype($path);
                $finder = new FileTypeBeanFinder($this->adapter);
                $finder->setFileType_Code($bean->getData('FileType_Code'));
                if ($finder->find() === 1) {
                    $type = $finder->getBean();
                    if ($type->getData('FileType_Mime') !== $mime) {
                        $filesystem->delete($path);
                        $this->getValidationHelper()->addError('Upload', $this->translate('file.upload.invalid'));
                        throw new \Exception('Invalid file type uploaded.');
                    }
                }*/
            }
        }
        parent::beforeSave($bean);
    }

    public function delete(): int
    {
        $beanList = $this->getBeanListForDelete();
        foreach ($beanList as $bean) {
            $filesystem = $this->getFilesystem($bean);
            if ($filesystem->has($bean->getData('File_Code'))) {
                $filesystem->delete($bean->getData('File_Code'));
            }
        }
        return parent::delete();
    }

    /**
     * @param BeanInterface $bean
     * @return Filesystem
     */
    protected function getFilesystem(BeanInterface $bean): Filesystem
    {
        $filesystemAdapter = new Local($this->getDirectoryPath($bean));
        return new Filesystem($filesystemAdapter);
    }

    protected function getDirectoryPath(BeanInterface $bean) {
        $path = implode(DIRECTORY_SEPARATOR, [
            $_SERVER["DOCUMENT_ROOT"], 'upload'
        ]);
        if ($bean->hasData('FileDirectory_ID')) {
            $finder = new FileDirectoryBeanFinder($this->adapter);
            $finder->setFileDirectory_ID($bean->getData('FileDirectory_ID'));
            if ($finder->find() === 1) {
                $directory = $finder->getBean();
                $path = implode(DIRECTORY_SEPARATOR, [
                    $_SERVER["DOCUMENT_ROOT"], 'upload', $directory->getData('FileDirectory_Code')
                ]);

            }
        }
        return $path;
    }

    protected function getFilePath(BeanInterface $bean) {
        $path = implode(DIRECTORY_SEPARATOR, [
            $_SERVER["DOCUMENT_ROOT"], 'upload', $bean->getData('File_Code')
        ]);
        if ($bean->hasData('FileDirectory_ID')) {
            $finder = new FileDirectoryBeanFinder($this->adapter);
            $finder->setFileDirectory_ID($bean->getData('FileDirectory_ID'));
            if ($finder->find() === 1) {
                $directory = $finder->getBean();
                $path = implode(DIRECTORY_SEPARATOR, [
                    $_SERVER["DOCUMENT_ROOT"], 'upload', $directory->getData('FileDirectory_Code'), $bean->getData('File_Code')
                ]);
            }
        }
        return $path;
    }


    protected function translate(string $name): string
    {
        return $this->getTranslator()->translate($name, 'validation');
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        if (!$bean->hasData('FileDirectory_ID') || !strlen(trim(($bean->getData('FileDirectory_ID'))))) {
            $this->getValidationHelper()->addError('FileDirectory_ID', $this->translate('filedirectory.code.empty'));
        }
        if (!$bean->hasData('FileType_Code') || !strlen(trim(($bean->getData('FileType_Code'))))) {
            $this->getValidationHelper()->addError('FileType_Code', $this->translate('filetype.code.empty'));
        } else {
            $finder = new FileTypeBeanFinder($this->adapter);
            $finder->setFileType_Code($bean->getData('FileType_Code'));
            $finder->setFileType_Active(true);
            if ($finder->count() !== 1) {
                $this->getValidationHelper()->addError('FileType_Code', $this->translate('filetype.code.invalid'));
            }
        }

        if ($bean->hasData('Upload')) {
            $upload = $bean->getData('Upload');
            if ($upload instanceof UploadedFile) {
                if ($upload->getError() != UPLOAD_ERR_OK) {
                    $this->getValidationHelper()->addError('Upload', $this->translate('file.upload.error'));
                }
            }
        } elseif (!$bean->hasData('File_ID')) {
            $this->getValidationHelper()->addError('Upload', $this->translate('file.upload.empty'));
        }

        return !$this->getValidationHelper()->hasError();
    }


}
