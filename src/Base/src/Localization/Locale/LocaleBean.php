<?php

namespace Pars\Base\Localization\Locale;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class LocaleBean
 * @package Pars\Base\Localization\Locale
 */
class LocaleBean extends AbstractBaseBean
{

    /**
     * LocaleBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('Locale_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('Locale_Name', self::DATA_TYPE_STRING, true);
        $this->setDataType('Locale_UrlCode', self::DATA_TYPE_STRING, true);
        $this->setDataType('Locale_Active', self::DATA_TYPE_BOOL, true);
        $this->setDataType('Locale_Order', self::DATA_TYPE_INT, true);
    }
}
