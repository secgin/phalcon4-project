<?php

namespace App\Providers;

use Phalcon\Config\Adapter\Json;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class ConfigProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $rootPath = $di->offsetGet('rootPath');

        $di->setShared('config', function () use ($rootPath) {
            return new Json($rootPath . '/config.json');
        });
    }
}