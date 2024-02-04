<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Router;

class RouterProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('router', function () {
           $router = new Router();

           $routerGroup = new Router\Group();
           $routerGroup->setPaths([
               'namespace' => 'App\Controllers'
           ]);
           $routerGroup->addGet('/', 'Index::index');
           $routerGroup->addGet('/{name}', 'Index::index');
           $routerGroup->addGet('/users', 'Index::find');
           $routerGroup->addGet('/users/{userId:[0-9]+}', 'Index::findFirst');

           $router->mount($routerGroup);

           return $router;
        });
    }
}