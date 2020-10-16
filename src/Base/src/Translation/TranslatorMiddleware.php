<?php


namespace Base\Translation;


use Base\Localization\LocalizationMiddleware;
use Base\Logging\LoggingMiddleware;
use Laminas\I18n\Translator\Translator;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TranslatorMiddleware implements MiddlewareInterface
{
    public const TRANSLATOR_ATTRIBUTE = 'translater';

    /**
     * @var Translator
     */
    private Translator $translator;

    /**
     * TranslationMiddleware constructor.
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $locale = $request->getAttribute(LocalizationMiddleware::LOCALIZATION_ATTRIBUTE);
        $logger = $request->getAttribute(LoggingMiddleware::LOGGER_ATTRIBUTE);
        $this->translator->setLocale($locale);

        $this->translator->enableEventManager();

        if ($this->translator->isEventManagerEnabled()) {
            $this->translator->getEventManager()->attach(
                \Laminas\I18n\Translator\Translator::EVENT_MISSING_TRANSLATION,
                static function (\Laminas\EventManager\EventInterface $event) use ($logger) {
                    $logger->warning('Missing translation', $event->getParams());
                }
            );
            $this->translator->getEventManager()->attach(
                \Laminas\I18n\Translator\Translator::EVENT_NO_MESSAGES_LOADED,
                static function (\Laminas\EventManager\EventInterface $event) use ($logger) {
                    $logger->error('No messages loaded', $event->getParams());
                }
            );
        }

        return $handler->handle($request->withAttribute(self::TRANSLATOR_ATTRIBUTE, $this->translator));
    }
}
