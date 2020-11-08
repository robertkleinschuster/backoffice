<?php

namespace Pars\Base\Cms\Paragraph\State;



use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class CmsParagraphStateBeanFactory
 * @package Pars\Base\Cms\Paragraph\State
 */
class CmsParagraphStateBeanFactory extends AbstractBeanFactory
{


    protected function getBeanClass(array $data): string
    {
        return CmsParagraphStateBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsParagraphStateBeanList::class;
    }


}
