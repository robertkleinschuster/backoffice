<?php

namespace Pars\Base\Translation\TranslationLoader;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class TranslationBean
 * @package Pars\Base\Translation\TranslationLoader
 */
class TranslationBean extends AbstractBaseBean
{
    public function __construct()
    {
        $this->setDataType('Translation_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('Translation_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('Translation_Namespace', self::DATA_TYPE_STRING, true);
        $this->setDataType('Locale_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('Translation_Text', self::DATA_TYPE_STRING, true);
        $this->setData('Translation_Namespace', 'default');
    }
}
