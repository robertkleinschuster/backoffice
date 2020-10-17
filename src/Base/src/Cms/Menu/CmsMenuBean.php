<?php


namespace Base\Cms\Menu;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsMenuBean extends AbstractJsonSerializableBean
{

    /**
     * CmsMenuBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsMenu_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('CmsMenu_ID_Parent', self::DATA_TYPE_INT, true);
        $this->setDataType('CmsSite_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('Translation_Code_Title', self::DATA_TYPE_STRING, true);
        $this->setDataType('Translation_Translation_Code_Title', self::DATA_TYPE_STRING, true);
        $this->setDataType('Translation_Text_Title', self::DATA_TYPE_STRING, true);
        $this->setDataType('Locale_Code_Title', self::DATA_TYPE_STRING, true);
    }

}
