<?php


namespace Base\Session\Cache;


use Cache\Adapter\Memcached\MemcachedCachePool;
use Psr\Container\ContainerInterface;

class MemcachedCachePoolFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $host = $config['mezzio-session-cache']['memcached_host'];
        $port = $config['mezzio-session-cache']['memcached_port'];
        $client = new \Memcached();
        $client->addServer($host, $port);
        return new MemcachedCachePool($client);
    }

}
