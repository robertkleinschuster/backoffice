<?php
namespace Frontend\Handler\Cms;

use Base\Cms\Menu\CmsMenuBeanFinder;
use Base\Cms\Site\CmsSiteBeanFinder;
use Base\Database\DatabaseMiddleware;
use Base\Localization\Locale\LocaleBeanFinder;
use Base\Localization\LocalizationMiddleware;
use Base\Translation\TranslatorMiddleware;
use Laminas\Diactoros\Response\HtmlResponse;
use Locale;
use Mezzio\Helper\UrlHelper;
use Mezzio\Template\TemplateRendererInterface;
use Minifier\TinyMinify;
use Mvc\Helper\PathHelper;

class CmsHandler implements \Psr\Http\Server\RequestHandlerInterface
{

    private TemplateRendererInterface $renderer;

    private $urlHelper;

    /**
     * CmsHandler constructor.
     * @param TemplateRendererInterface $renderer
     */
    public function __construct(TemplateRendererInterface $renderer, UrlHelper $urlHelper)
    {
        $this->renderer = $renderer;
        $this->urlHelper = $urlHelper;

    }

    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $adapter = $request->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE);
        $locale = $request->getAttribute(LocalizationMiddleware::LOCALIZATION_ATTRIBUTE);
        $translator = $request->getAttribute(TranslatorMiddleware::TRANSLATOR_ATTRIBUTE);
        $code = $request->getAttribute('code', '/');
        $placeholder = new CmsPlaceholder($locale);
        $placeholder->setTranslator($translator);

        $menuFinder = new CmsMenuBeanFinder($adapter);
        $menuFinder->findByLocaleWithFallback($locale, 'de_AT');

        $this->renderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'menu', $menuFinder->getBeanGenerator());
        $this->renderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'code', $code);
        $this->renderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'placeholder', $placeholder);
        $this->renderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'url', function($code) {
            if (trim($code) == '/' || trim($code) == '') {
                return $this->urlHelper->generate(null, ['code' => null]);
            }
            return $this->urlHelper->generate(null, ['code' => str_replace('/','' , $code)]);
        });
        $siteFinder = new CmsSiteBeanFinder($adapter);
        $siteFinder->setArticleTranslation_Code($code);
        if ($siteFinder->findByLocaleWithFallback($locale, 'de_AT') === 1) {
            $bean = $siteFinder->getBean();
            $this->renderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'bean', $bean);
            return new HtmlResponse(TinyMinify::html($this->renderer->render($bean->getData('CmsSiteType_Template'))));
        }
        return new HtmlResponse(TinyMinify::html($this->renderer->render('frontend::404')), 404);
    }
}
