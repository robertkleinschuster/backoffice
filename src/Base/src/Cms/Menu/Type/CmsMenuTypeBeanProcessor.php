<?php

namespace Pars\Base\Cms\Menu\Type;

use Pars\Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Processor\AbstractBeanProcessor;
use Niceshops\Bean\Type\Base\BeanInterface;

/**
 * Class CmsMenuTypeBeanProcessor
 * @package Pars\Base\Cms\Menu\Type
 */
class CmsMenuTypeBeanProcessor extends AbstractBeanProcessor
{

    /**
     * ArticleStateBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsMenuType_Code', 'CmsMenuType_Code', 'CmsMenuType', 'CmsMenuType_Code', true);
        $saver->addColumn('CmsMenuType_Active', 'CmsMenuType_Active', 'CmsMenuType', 'CmsMenuType_Code');
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
