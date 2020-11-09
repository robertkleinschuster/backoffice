<?php

namespace Pars\Base\Cms\Page\State;

use Niceshops\Bean\Factory\AbstractBeanFactory;


/**
 * Class CmsPageStateBeanFactory
 * @package Pars\Base\Cms\Page\State
 */
class CmsPageStateBeanFactory extends AbstractBeanFactory
{


    protected function getBeanClass(array $data): string
    {
        return CmsPageStateBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsPageStateBeanList::class;
    }


}
