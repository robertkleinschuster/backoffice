<?php


namespace Base\Cms\Menu;


use Base\Database\DatabaseBeanLoader;
use Base\Translation\TranslationLoader\TranslationBeanFinder;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class CmsMenuBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsMenu_ID', 'CmsMenu_ID', 'CmsMenu', 'CmsMenu_ID', true);
        $loader->addColumn('CmsMenu_ID_Parent', 'CmsMenu_ID_Parent', 'CmsMenu', 'CmsMenu_ID');
        $loader->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsMenu', 'CmsMenu_ID');
        $loader->addColumn('Translation_Code_Title', 'Translation_Code_Title', 'CmsMenu', 'CmsMenu_ID');
        $loader->addColumn(
            'Translation_Translation_Code_Title',
            'Translation_Code',
            'Translation',
            'Translation_Translation_Code_Title',
            false,
            'Translation_Code_Title'
        );
        $loader->addColumn(
            'Translation_Text_Title',
            'Translation_Text',
            'Translation',
            'Translation_Translation_Code_Title',
            false,
            'Translation_Code_Title'
        );
        $loader->addColumn(
            'Locale_Code_Title',
            'Locale_Code',
            'Translation',
            'Translation_Translation_Code_Title',
            false,
            'Translation_Code_Title'
        );


        parent::__construct($loader, new CmsMenuBeanFactory());
        #$this->linkBeanFinder(new TranslationBeanFinder($adapter), 'Translation_Title_BeanList', 'Translation_Code_Title', 'Translation_Code');
    }

    /**
     * @param string $locale_Code
     * @return $this
     */
    public function setLocale_Code(string $locale_Code): self
    {
        $this->getLoader()->filterValue('Locale_Code_Title', $locale_Code);
        return $this;
    }

}
