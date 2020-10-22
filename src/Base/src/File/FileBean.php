<?php


namespace Base\File;


use Base\Article\Translation\ArticleTranslationBean;

class FileBean extends ArticleTranslationBean
{

    /**
     * FileBean constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDataType('File_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('File_Name', self::DATA_TYPE_STRING, true);
        $this->setDataType('FileType_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('FileDirectory_Code', self::DATA_TYPE_STRING, true);
    }

}
