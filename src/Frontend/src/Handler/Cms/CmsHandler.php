<?php
namespace Frontend\Handler\Cms;

use Base\Cms\Menu\CmsMenuBeanFinder;
use Base\Cms\Site\CmsSiteBeanFinder;
use Base\Database\DatabaseMiddleware;
use Base\Localization\LocalizationMiddleware;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Minifier\TinyMinify;

class CmsHandler implements \Psr\Http\Server\RequestHandlerInterface
{

    private TemplateRendererInterface $renderer;

    /**
     * CmsHandler constructor.
     * @param TemplateRendererInterface $renderer
     */
    public function __construct(TemplateRendererInterface $renderer, array $config)
    {
        $this->renderer = $renderer;

    }


    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $adapter = $request->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE);
        $locale = $request->getAttribute(LocalizationMiddleware::LOCALIZATION_ATTRIBUTE);
        $code = $request->getAttribute('code', '/');
        $lang = \Locale::getPrimaryLanguage($locale);

        $menuFinder = new CmsMenuBeanFinder($adapter);
        $menuFinder->setLocale_Code($locale);
        $menuFinder->find();

        $this->renderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'menu', $menuFinder->getBeanGenerator());
        $this->renderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'code', $code);
        $siteFinder = new CmsSiteBeanFinder($adapter);
        $siteFinder->setLocale_Code($locale, false);
        $siteFinder->setArticleTranslation_Code($code);
        if ($siteFinder->count() == 0) {
            $siteFinder = new CmsSiteBeanFinder($adapter);
            $siteFinder->setLanguage($lang);
            $siteFinder->setArticleTranslation_Code($code);
            if ($siteFinder->count() == 0) {
                $siteFinder = new CmsSiteBeanFinder($adapter);
                $siteFinder->setLocale_Code('de_AT');
                $siteFinder->setArticleTranslation_Code($code);
            }
        }
        if ($siteFinder->find() === 1) {
            $this->renderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'bean', $siteFinder->getBean());
            return new HtmlResponse(TinyMinify::html($this->renderer->render('frontend::index')));
        }
        return new HtmlResponse(TinyMinify::html($this->renderer->render('frontend::404')), 404);
    }
}
