<?php

declare(strict_types=1);

namespace Backoffice;

use Backoffice\Authentication\AuthenticationMiddleware;
use Backoffice\Authentication\AuthenticationMiddlewareFactory;
use Backoffice\Authentication\Bean\UserBeanFactory;
use Backoffice\Authentication\UserRepositoryFactory;
use Backoffice\Mvc\Authentication\AuthenticationController;
use Backoffice\Mvc\Authentication\AuthenticationModel;
use Backoffice\Mvc\Index\IndexController;
use Backoffice\Mvc\Index\IndexModel;
use Backoffice\Mvc\Role\RoleController;
use Backoffice\Mvc\Role\RoleModel;
use Backoffice\Mvc\RolePermission\RolePermissionController;
use Backoffice\Mvc\RolePermission\RolePermissionModel;
use Backoffice\Mvc\Update\UpdateController;
use Backoffice\Mvc\Update\UpdateModel;
use Backoffice\Mvc\User\UserModel;
use Backoffice\Mvc\User\UserController;
use Backoffice\Session\Cache\FilesystemCachePoolFactory;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Session\Cache\CacheSessionPersistence;
use Mezzio\Session\SessionPersistenceInterface;

/**
 * The configuration provider for the Backoffice module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'db' => [
              #  'driver'   => 'Pdo_Sqlite',
              #  'database' => __DIR__ . '/../../../data/sqlite.db',
                'driver' => 'Pdo_Mysql',
                'database' => 'backoffice',
                'username' => 'backoffice',
                'password' => 'backoffice',
                'hostname' => '127.0.0.1'
            ],
            'mvc' => [
                'controllers' => [
                    'index' => IndexController::class,
                    'auth' => AuthenticationController::class,
                    'user' => UserController::class,
                    'update' => UpdateController::class,
                    'role' => RoleController::class,
                    'rolepermission' => RolePermissionController::class,
                ],
                'models' => [
                    'index' => IndexModel::class,
                    'auth' => AuthenticationModel::class,
                    'user' => UserModel::class,
                    'update' => UpdateModel::class,
                    'role' => RoleModel::class,
                    'rolepermission' => RolePermissionModel::class,
                ],
            ],
            'mezzio-session-cache' => [
                // Detailed in the above section; allows using a different
                // cache item pool than the global one.
                'cache_item_pool_service' => 'SessionCache',

                'filesystem_folder' => __DIR__ . '/../../../data/session',

                // The name of the session cookie. This name must comply with
                // the syntax outlined in https://tools.ietf.org/html/rfc6265.html
                'cookie_name' => 'Backoffice-Session',

                // The (sub)domain that the cookie is available to. Setting this
                // to a subdomain (such as 'www.example.com') will make the cookie
                // available to that subdomain and all other sub-domains of it
                // (i.e. w2.www.example.com). To make the cookie available to the
                // whole domain (including all subdomains of it), simply set the
                // value to the domain name ('example.com', in this case).
                // Leave this null to use browser default (current hostname).
                'cookie_domain' => null,

                // The path prefix of the cookie domain to which it applies.
                'cookie_path' => '/',

                // Indicates that the cookie should only be transmitted over a
                // secure HTTPS connection from the client. When set to TRUE, the
                // cookie will only be set if a secure connection exists.
                'cookie_secure' => false,

                // When TRUE the cookie will be made accessible only through the
                // HTTP protocol. This means that the cookie won't be accessible
                // by scripting languages, such as JavaScript.
                'cookie_http_only' => false,

                // Available since 1.4.0
                //
                // Asserts that a cookie must not be sent with cross-origin requests,
                // providing some protection against cross-site request forgery attacks (CSRF).
                //
                // Allowed values:
                // - Strict: The browser sends the cookie only for same-site requests
                //   (that is, requests originating from the same site that set the cookie).
                //   If the request originated from a different URL than the current one,
                //   no cookies with the SameSite=Strict attribute are sent.
                // - Lax: The cookie is withheld on cross-site subrequests, such as calls
                //   to load images or frames, but is sent when a user navigates to the URL
                //   from an external site, such as by following a link.
                // - None: The browser sends the cookie with both cross-site and same-site
                //   requests.
                'cookie_same_site' => 'Lax',

                // Governs the various cache control headers emitted when
                // a session cookie is provided to the client. Value may be one
                // of "nocache", "public", "private", or "private_no_expire";
                // semantics are the same as outlined in
                // http://php.net/session_cache_limiter
                'cache_limiter' => 'nocache',

                // When the cache and the cookie should expire, in seconds. Defaults
                // to 180 minutes.
                'cache_expire' => 86400,

                // An integer value indicating when the resource to which the session
                // applies was last modified. If not provided, it uses the last
                // modified time of, in order,
                // - the public/index.php file of the current working directory
                // - the index.php file of the current working directory
                // - the current working directory
                'last_modified' => null,

                // A boolean value indicating whether or not the session cookie
                // should persist. By default, this is disabled (false); passing
                // a boolean true value will enable the feature. When enabled, the
                // cookie will be generated with an Expires directive equal to the
                // the current time plus the cache_expire value as noted above.
                //
                // As of 1.2.0, developers may define the session TTL by calling the
                // session instance's `persistSessionFor(int $duration)` method. When
                // that method has been called, the engine will use that value even if
                // the below flag is toggled off.
                'persistent' => true,
            ],
            'authentication' => [
                'redirect' => '/auth/login',
                'username' => 'login_username',
                'password' => 'login_password'
            ],
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'aliases' => [
                SessionPersistenceInterface::class => CacheSessionPersistence::class,
                AuthenticationInterface::class => PhpSession::class,
            ],
            'invokables' => [
            ],
            'factories'  => [
                'SessionCache' => FilesystemCachePoolFactory::class,
                AuthenticationMiddleware::class => AuthenticationMiddlewareFactory::class,
                UserRepositoryInterface::class => UserRepositoryFactory::class,
                UserInterface::class => UserBeanFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
                'mvc'    => [__DIR__ . '/../templates/mvc'],
            ],
        ];
    }
}
