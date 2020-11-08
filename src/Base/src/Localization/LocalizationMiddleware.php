<?php

namespace Pars\Base\Localization;

use Pars\Base\Database\DatabaseMiddleware;
use Pars\Base\Localization\Locale\LocaleBeanFinder;
use Laminas\Diactoros\Response\RedirectResponse;
use Locale;
use Mezzio\Helper\UrlHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class LocalizationMiddleware
 * @package Pars\Base\Localization
 */
class LocalizationMiddleware implements MiddlewareInterface
{

    /**
     * @var UrlHelper
     */
    private $urlHelper;


    public const LOCALIZATION_ATTRIBUTE = 'locale';

    /**
     * LocalizationMiddleware constructor.
     * @param UrlHelper $urlHelper
     */
    public function __construct(UrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
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
                    $finder->limit(1, 0);
                    if ($finder->count() == 1) {
                        $this->urlHelper->setBasePath($finder->getBean()->getData('Locale_UrlCode'));
                    } else {
                        $locale = false;
                    }
                }
                if ($locale === false) {
                    $finder = new LocaleBeanFinder($adapter);
                    $finder->setLanguage(Locale::getPrimaryLanguage(Locale::acceptFromHttp($request->getServerParams()['HTTP_ACCEPT_LANGUAGE'])));
                    $finder->setLocale_Active(true);
                    $finder->limit(1, 0);
                    if ($finder->count() == 1) {
                        $this->urlHelper->setBasePath($finder->getBean()->getData('Locale_UrlCode'));
                    } else {
                        $locale = false;
                    }
                }
                if ($locale === false) {
                    $finder = new LocaleBeanFinder($adapter);
                    $finder->setLocale_Active(true);
                    $finder->limit(1, 0);
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
