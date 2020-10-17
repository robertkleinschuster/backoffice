<?php


namespace Base\Translation\TranslationLoader;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class TranslationBean extends AbstractJsonSerializableBean
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
