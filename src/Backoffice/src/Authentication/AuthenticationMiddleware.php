<?php


namespace Backoffice\Authentication;


use Laminas\Diactoros\UriFactory;
use Mezzio\Mvc\Helper\PathHelper;
use Mezzio\Mvc\Helper\PathHelperFactory;
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
        if ($request->getUri()->getPath() === $this->container->get('config')['authentication']['redirect']
        && $request->getMethod() !== 'POST') {
            return $handler->handle($request);
        }
        $authenticationMiddleware = $this->container->get(
            \Mezzio\Authentication\AuthenticationMiddleware::class
        );
        return $authenticationMiddleware->process($request, $handler);
    }

}
