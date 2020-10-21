<?php


namespace Base\Cms\Paragraph\State;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsParagraphStateBeanProcessor extends AbstractBeanProcessor
{


    /**
     * CmsParagraphStateBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsParagraphState_Code', 'CmsParagraphState_Code', 'CmsParagraphState', 'CmsParagraphState_Code', true);
        $saver->addColumn('CmsParagraphState_Active', 'CmsParagraphState_Active', 'CmsParagraphState', 'CmsParagraphState_Code');
        parent::__construct($saver);
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        return true;
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
       return true;
    }

}
