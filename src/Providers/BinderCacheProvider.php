<?php

namespace App\Providers;

use Phalcon\Cache\AdapterFactory;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Storage\SerializerFactory;

class BinderCacheProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->set('dispatcherBinderCacheAdapter', function () {
            $options = [
                'lifetime' => 7200,
                'defaultSerializer' => 'Json',
            ];

            $serializerFactory = new SerializerFactory();
            $adapterFactory = new AdapterFactory($serializerFactory, $options);
            return $adapterFactory->newInstance('memory', $options);
        });
    }
}