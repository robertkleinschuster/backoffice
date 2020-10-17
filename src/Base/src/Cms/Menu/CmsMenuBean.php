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
        $this->setDataType('Article_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('Article_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('Locale_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Name', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Code', self::DATA_TYPE_STRING, true);
    }

}
