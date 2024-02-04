<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\View;

class ViewProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $viewsDir = $di->get('config')->application->viewsDir;

        $di->setShared('view', function () use ($viewsDir) {
            $view = new View();
            $view->setDI($this);
            $view->setViewsDir($viewsDir);

            return $view;
        });
    }
}