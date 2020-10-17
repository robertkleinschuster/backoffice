<?php


namespace Base\Article\Type;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class ArticleTypeBeanProcessor extends AbstractBeanProcessor
{

    /**
     * ArticleStateBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('ArticleType_Code', 'ArticleType_Code', 'ArticleType', 'ArticleType_Code', true);
        $saver->addColumn('ArticleType_Active', 'ArticleType_Active', 'ArticleType', 'ArticleType_Code');
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
