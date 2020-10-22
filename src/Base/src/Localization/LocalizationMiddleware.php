<?php


namespace Base\Localization;


use Base\Database\DatabaseMiddleware;
use Base\Localization\Locale\LocaleBeanFinder;
use Laminas\Diactoros\Response\RedirectResponse;
use Locale;
use Mezzio\Helper\UrlHelper;
use Mvc\Helper\PathHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LocalizationMiddleware implements MiddlewareInterface
{

    /**
     * @var UrlHelper
     */
    private $urlHelper;



    public const LOCALIZATION_ATTRIBUTE = 'locale';

    /**
     * LocalizationMiddleware constructor.
     * @param PathHelper $urlHelper
     */
    public function __construct(UrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        try {
            $adapter = $request->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE);
            $locale = $request->getAttribute('locale', false);
            $locale = Locale::acceptFromHttp($locale);
            if ($locale === false) {
                $locale = Locale::acceptFromHttp($request->getServerParams()['HTTP_ACCEPT_LANGUAGE']);
                if ($locale !== false) {
                    $finder = new LocaleBeanFinder($adapter);
                    $finder->setLocale_Code($locale);
                    $finder->setLocale_Active(true);
                    $finder->limit(1,0);
                    if ($finder->count() == 1) {
                        $finder->find();
                        $this->urlHelper->setBasePath($finder->getBean()->getData('Locale_UrlCode'));
                    } else {
                        $locale = false;
                    }
                }
                if ($locale === false) {
                    $finder = new LocaleBeanFinder($adapter);
                    $finder->setLanguage(Locale::getPrimaryLanguage(Locale::acceptFromHttp($request->getServerParams()['HTTP_ACCEPT_LANGUAGE'])));
                    $finder->setLocale_Active(true);
                    $finder->limit(1,0);
                    if ($finder->count() == 1) {
                        $finder->find();
                        $this->urlHelper->setBasePath($finder->getBean()->getData('Locale_UrlCode'));
                    } else {
                        $locale = false;
                    }
                }
                if ($locale === false) {
                    $finder = new LocaleBeanFinder($adapter);
                    $finder->setLocale_Active(true);
                    $finder->limit(1,0);
                    $finder->find();
                    $this->urlHelper->setBasePath($finder->getBean()->getData('Locale_UrlCode'));
                }
                return new RedirectResponse($this->urlHelper->generate());
            }
        } catch (\Exception $exception) {
            $this->urlHelper->setBasePath('de-AT');
            return new RedirectResponse($this->urlHelper->generate());
        }
        return $handler->handle($request->withAttribute(self::LOCALIZATION_ATTRIBUTE, $locale));
    }
}
