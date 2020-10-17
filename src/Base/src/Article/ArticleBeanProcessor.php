<?php


namespace Base\Article;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class ArticleBeanProcessor extends AbstractBeanProcessor
{
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('Article_ID', 'Article_ID', 'Article', 'Article_ID', true);
        $saver->addColumn('Article_Code', 'Article_Code', 'Article', 'Article_ID');
        $saver->addColumn('ArticleState_Code', 'ArticleState_Code', 'Article', 'Article_ID');
        $saver->addColumn('ArticleType_Code', 'ArticleType_Code', 'Article', 'Article_ID');
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
