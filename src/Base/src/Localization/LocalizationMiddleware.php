<?php


namespace Base\Localization;


use Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LocalizationMiddleware implements MiddlewareInterface
{
    public const LOCALIZATION_ATTRIBUTE = 'locale';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // Get locale from route, fallback to the user's browser preference
        $locale = $request->getAttribute(
            'locale',
            Locale::acceptFromHttp(
                $request->getServerParams()['HTTP_ACCEPT_LANGUAGE'] ?? 'en_US'
            )
        );

        // Store the locale as a request attribute
        return $handler->handle($request->withAttribute(self::LOCALIZATION_ATTRIBUTE, $locale));
    }
}
