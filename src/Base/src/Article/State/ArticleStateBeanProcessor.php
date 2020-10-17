<?php


namespace Base\Article\State;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class ArticleStateBeanProcessor extends AbstractBeanProcessor
{


    /**
     * ArticleStateBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('ArticleState_Code', 'ArticleState_Code', 'ArticleState', 'ArticleState_Code', true);
        $saver->addColumn('ArticleState_Active', 'ArticleState_Active', 'ArticleState', 'ArticleState_Code');
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
