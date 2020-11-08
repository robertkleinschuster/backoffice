<?php

namespace Pars\Base\Cms\Paragraph;

use Niceshops\Bean\Factory\AbstractBeanFactory;


/**
 * Class CmsParagraphBeanFactory
 * @package Pars\Base\Cms\Paragraph
 */
class CmsParagraphBeanFactory extends AbstractBeanFactory
{


    protected function getBeanClass(array $data): string
    {
        return CmsParagraphBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsParagraphBeanList::class;
    }


}
