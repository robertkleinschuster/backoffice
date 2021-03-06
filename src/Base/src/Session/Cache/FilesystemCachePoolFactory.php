<?php
namespace Base\Session\Cache;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Psr\Container\ContainerInterface;

class FilesystemCachePoolFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $filesystemAdapter = new Local($config['mezzio-session-cache']['filesystem_folder']);
        $filesystem        = new Filesystem($filesystemAdapter);

        return new FilesystemCachePool($filesystem);
    }


}
