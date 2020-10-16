<?php


namespace Base\Authentication;

use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Mvc\Helper\PathHelper;
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
     * @var PathHelper
     */
    private $pathHelper;

    /**
     * AuthenticationMiddleware constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->auth = $container->get(AuthenticationInterface::class);
        $this->pathHelper = $container->get(PathHelper::class);
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

        $redirect = $this->pathHelper
            ->setController( $config['authentication']['redirect']['controller'])
            ->setAction( $config['authentication']['redirect']['action'])
            ->getPath();

        $whitelist = [];
        $whitelist[] = $this->normalizePath($redirect);
        foreach ($config['authentication']['whitelist'] as $item) {
            $path = $this->pathHelper->setController($item['controller'])->setAction($item['action'])->getPath();
            $whitelist[] = $this->normalizePath($path);
        }
        $current = $this->normalizePath($request->getUri()->getPath());




        $user = null;
        // Validation CSRF Token
        if ($request->getMethod() === 'POST') {
            if ($guard->validateToken($request->getParsedBody()['login_token'] ?? '', 'login_token')) {
                $user = $this->auth->authenticate($request);
                if ($user === null) {
                    $flash->flash('login_error', 'Ungültiger Benutzername oder Passwort.');
                    return new RedirectResponse($redirect);
                }
            }
        } else {
            $user = $this->auth->authenticate($request);
        }

        if ($user !== null) {
            return $handler->handle($request->withAttribute(UserInterface::class, $user));
        }

        if (in_array($current, $whitelist)) {
            return $handler->handle($request);
        }

        return new RedirectResponse($redirect);
    }



    protected function normalizePath(string $path) {
        return trim(strtolower(str_replace('/', '', $path)));
    }
}
