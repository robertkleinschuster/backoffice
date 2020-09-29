<?php


namespace Backoffice\Authentication;


use Laminas\Diactoros\UriFactory;
use Mezzio\Csrf\CsrfGuardInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessagesInterface;
use Mezzio\Mvc\Helper\PathHelper;
use Mezzio\Mvc\Helper\PathHelperFactory;
use Mezzio\Mvc\Helper\ValidationHelper;
use Mezzio\Session\SessionMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{

    private $container;

    /**
     * AuthenticationMiddleware constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $guard = $request->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
        $flash = $request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
        $config = $this->container->get('config');
        if ($request->getUri()->getPath() === $config['authentication']['redirect']) {
            if ($request->getMethod() === 'GET') {
                return $handler->handle($request);
            } elseif ($request->getMethod() === 'POST') {
                $token = $request->getParsedBody()['token'];
                if((!$token || !$guard->validateToken($token)) && $request->getMethod() === 'POST') {
                    $flash->flash('validationErrorMap', $this->getErrorFieldMap($config));
                    return $handler->handle($request)->withHeader(
                        'Location',
                        $config['authentication']['redirect'])
                        ->withStatus(302);
                }
            }
        }
        $authenticationMiddleware = $this->container->get(
            \Mezzio\Authentication\AuthenticationMiddleware::class
        );
        $response = $authenticationMiddleware->process($request, $handler);
        if ($response->getStatusCode() == 302 && $request->getMethod() === 'POST') {
            $flash->flash('validationErrorMap', $this->getErrorFieldMap($config));
        }
        return $response;
    }

    protected function getErrorFieldMap($config)
    {
        $validationHelper = new ValidationHelper();
        $validationHelper->addError($config['authentication']['username'], 'Ungültiger Benutzername oder Passwort.');
        $validationHelper->addError($config['authentication']['password'], 'Ungültiger Benutzername oder Passwort.');
        return $validationHelper->getErrorFieldMap();
    }

}
