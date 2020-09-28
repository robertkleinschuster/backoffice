<?php


namespace Backoffice\Mvc\Model;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Mezzio\Mvc\Model\AbstractModel;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanFinder\BeanFinderInterface;
use NiceshopsDev\Bean\BeanProcessor\BeanProcessorInterface;

abstract class BaseModel extends AbstractModel implements AdapterAwareInterface
{
    use AdapterAwareTrait;

    public const SUBMIT_ATTRIBUTE = 'submit';

    /**
     * @return Adapter
     */
    public function getDbAdpater(): Adapter
    {
        return $this->adapter;
    }

}
