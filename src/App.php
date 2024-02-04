<?php

namespace App;

use App\Providers\BinderCacheProvider;
use App\Providers\ConfigProvider;
use App\Providers\DbProvider;
use App\Providers\DispatcherProvider;
use App\Providers\RouterProvider;
use App\Providers\UrlProvider;
use App\Providers\ViewProvider;
use Phalcon\Di\DiInterface;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;

final class App
{
    private Application $application;

    private DiInterface $container;

    private string $rootPath;

    public function __construct()
    {
        $this->rootPath = dirname(__DIR__);
        $this->container = new FactoryDefault();
        $this->application = new Application($this->container);
        $this->initializeProviders();
    }

    public function run(): void
    {
        try
        {
            $this->setDispatcherBinder();
            $response = $this->application->handle($this->getRequestUri());
            $response->send();
        } catch (\Exception $e)
        {
            echo 'Exception(' . get_class($e) . '): ', $e->getMessage();

            print_r($e->getTrace());
        }
    }

    private function getRequestUri(): string
    {
        $baseUri = $this->container->getShared('url')->getBaseUri();
        $position = strpos($_SERVER['REQUEST_URI'], $baseUri) + strlen($baseUri);
        return '/' . substr($_SERVER['REQUEST_URI'], $position);
    }

    private function getProviders(): array
    {
        return [
            ConfigProvider::class,
            BinderCacheProvider::class,
            DbProvider::class,
            ViewProvider::class,
            UrlProvider::class,
            RouterProvider::class,
            DispatcherProvider::class,
        ];
    }

    private function initializeProviders(): void
    {
        $rootPath = $this->rootPath;
        $this->container->offsetSet('rootPath', fn() => $rootPath);

        foreach ($this->getProviders() as $provider)
            (new $provider())->register($this->container);

        //foreach ($container->getServices() as $name => $service)
        //    echo $name . PHP_EOL;
    }

    private function setDispatcherBinder(): void
    {
        $dispatcherBinderCashAdapter = $this->container->get('dispatcherBinderCacheAdapter');
        $this->container->get('dispatcher')->setModelBinder(new Binder(), $dispatcherBinderCashAdapter);
    }
}