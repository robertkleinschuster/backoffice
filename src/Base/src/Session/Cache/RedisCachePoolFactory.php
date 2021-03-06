<?php


namespace Base\Session\Cache;


use Cache\Adapter\Redis\RedisCachePool;
use Psr\Container\ContainerInterface;

class RedisCachePoolFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $host = $config['mezzio-session-cache']['redis_host'];
        $port = $config['mezzio-session-cache']['redis_port'];

        $client = new \Redis();
        $client->connect($host, $port);
        $cachePool = new RedisCachePool($client);
        $cachePool->setLogger($container->get('Logger'));
        return $cachePool;
    }

}
