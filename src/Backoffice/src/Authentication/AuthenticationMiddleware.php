<?php


namespace Backoffice\Authentication;

use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{

    private $container;

    /**
     * @var AuthenticationInterface
     */
    private $auth;

    /**
     * AuthenticationMiddleware constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->auth = $container->get(AuthenticationInterface::class);
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
        // Allow GET to Auth Controller
        if ($request->getUri()->getPath() === $config['authentication']['redirect'] && $request->getMethod() === 'GET') {
            return $handler->handle($request);
        }
        $user = null;
        // Validation CSRF Token
        if ($request->getMethod() === 'POST') {
            if ($guard->validateToken($request->getParsedBody()['login_token'] ?? '', 'login_token')) {
                $user = $this->auth->authenticate($request);
            }
        } else {
            $user = $this->auth->authenticate($request);
        }
        if (null !== $user) {
            return $handler->handle($request->withAttribute(UserInterface::class, $user));
        } elseif ($request->getUri()->getPath() === $config['authentication']['redirect']) {
            $flash->flash('login_error', 'Ungültiger Benutzername oder Passwort.');
        }
        return $this->auth->unauthorizedResponse($request);
    }

}
