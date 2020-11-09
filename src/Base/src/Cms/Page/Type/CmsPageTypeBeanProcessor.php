<?php

namespace Pars\Base\Cms\Page\Type;

use Pars\Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Processor\AbstractBeanProcessor;

/**
 * Class CmsPageTypeBeanProcessor
 * @package Pars\Base\Cms\Page\Type
 */
class CmsPageTypeBeanProcessor extends AbstractBeanProcessor
{

    /**
     * CmsPageStateBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsPageType_Code', 'CmsPageType_Code', 'CmsPageType', 'CmsPageType_Code', true);
        $saver->addColumn('CmsPageType_Active', 'CmsPageType_Active', 'CmsPageType', 'CmsPageType_Code');
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
