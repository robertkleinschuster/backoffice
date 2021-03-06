<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

// To enable or disable caching, set the `ConfigAggregator::ENABLE_CACHE` boolean in
// `config/autoload/local.php`.
$cacheConfig = [
    'config_cache_path' => 'data/cache/config-cache.php',
];

$aggregator = new ConfigAggregator([
    \Laminas\Serializer\ConfigProvider::class,
    \Frontend\ConfigProvider::class,
    \Laminas\Cache\ConfigProvider::class,
    \Laminas\I18n\ConfigProvider::class,
    \Laminas\Log\ConfigProvider::class,
    \Mezzio\Flash\ConfigProvider::class,
    \Mezzio\Session\Cache\ConfigProvider::class,
    \Mezzio\Authentication\Session\ConfigProvider::class,
    \Mezzio\Csrf\ConfigProvider::class,
    \Mezzio\Session\ConfigProvider::class,
    \Mezzio\Authentication\ConfigProvider::class,
    \Laminas\Hydrator\ConfigProvider::class,
    \Laminas\Db\ConfigProvider::class,
    \Laminas\HttpHandlerRunner\ConfigProvider::class,
    \Mezzio\Plates\ConfigProvider::class,
    \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
    // Include cache configuration
    new ArrayProvider($cacheConfig),

    \Mezzio\Helper\ConfigProvider::class,
    \Mezzio\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,
    \Laminas\Diactoros\ConfigProvider::class,
    \Mvc\ConfigProvider::class,
    // Swoole config to overwrite some services (if installed)
    class_exists(\Mezzio\Swoole\ConfigProvider::class)
        ? \Mezzio\Swoole\ConfigProvider::class
        : function(): array { return[]; },

    // Module config here to overwrite default config
    \Base\ConfigProvider::class,
    \Backoffice\ConfigProvider::class,

    // Load application config in a pre-defined order in such a way that local settings
    // overwrite global settings. (Loaded as first to last):
    //   - `global.php`
    //   - `*.global.php`
    //   - `local.php`
    //   - `*.local.php`
    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),

    // Load development config if it exists
    new PhpFileProvider(realpath(__DIR__) . '/development.config.php'),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
