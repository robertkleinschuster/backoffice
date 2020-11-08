<?php

namespace Pars\Base\Cms\Site\State;

use Niceshops\Bean\Factory\AbstractBeanFactory;


/**
 * Class CmsSiteStateBeanFactory
 * @package Pars\Base\Cms\Site\State
 */
class CmsSiteStateBeanFactory extends AbstractBeanFactory
{


    protected function getBeanClass(array $data): string
    {
        return CmsSiteStateBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsSiteStateBeanList::class;
    }


}
