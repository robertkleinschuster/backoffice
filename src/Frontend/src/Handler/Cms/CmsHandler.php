<?php
namespace Frontend\Handler\Cms;

use Base\Cms\Menu\CmsMenuBeanFinder;
use Base\Cms\Site\CmsSiteBeanFinder;
use Base\Database\DatabaseMiddleware;
use Base\Localization\LocalizationMiddleware;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;

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

        $menuFilder = new CmsMenuBeanFinder($adapter);
        $menuFilder->setLocale_Code($locale);
        $menuFilder->find();

        $this->renderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'menu', $menuFilder->getBeanGenerator());
        $this->renderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'code', $code);
        $siteFinder = new CmsSiteBeanFinder($adapter);
        $siteFinder->setLocale_Code($locale);
        $siteFinder->setArticleTranslation_Code($code);
        if ($siteFinder->find() === 1) {
            $this->renderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'bean', $siteFinder->getBean());
            return new HtmlResponse($this->renderer->render('frontend::index'));
        }
        return new HtmlResponse($this->renderer->render('frontend::404'));
    }
}
