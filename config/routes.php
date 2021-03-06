<?php

declare(strict_types=1);

use Base\Authentication\AuthenticationMiddleware;
use Base\Database\DatabaseMiddleware;
use Base\Localization\LocalizationMiddleware;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Mvc\Handler\MvcHandler;
use Psr\Container\ContainerInterface;

/**
 * FastRoute route configuration
 *
 * @see https://github.com/nikic/FastRoute
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/{id:\d+}', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {

    $app->any(MvcHandler::getRoute('/backoffice'), [
        DatabaseMiddleware::class,
        AuthenticationMiddleware::class,
        MvcHandler::class
    ], 'backoffice');

    $app->any('[/[{code}]]', [
        DatabaseMiddleware::class,
        \Frontend\Handler\Cms\CmsHandler::class
    ]);

};
