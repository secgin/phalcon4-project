<?php

namespace App;

use Exception;
use Phalcon\Cache\Adapter\AdapterInterface;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Model\BinderInterface;
use ReflectionMethod;

class Binder extends Injectable implements BinderInterface
{
    private ?AdapterInterface $cache;

    public function __construct(AdapterInterface $cache = null)
    {
        $this->cache = $cache;
    }

    public function bindToHandler($handler, array $params, string $cacheKey, string $methodName = null): array
    {
        return $this->prepareMethodParameters($handler, $methodName, $params);
    }

    public function getBoundModels(): array
    {
        return [];
    }

    public function getCache(): AdapterInterface
    {
        return $this->cache;
    }

    public function setCache(AdapterInterface $cache): BinderInterface
    {
        $this->cache = $cache;
        return $this;
    }

    private function prepareMethodParameters($controller, string $actionName, array $params): array
    {
        $resultParameters = [];

        try
        {
            $reflection = new ReflectionMethod($controller, $actionName);
            $parameters = $reflection->getParameters();

            foreach ($parameters as $parameter)
            {
                if ($parameter->getType() != null and !$parameter->getType()->isBuiltin())
                {
                    $className = $parameter->getType()->getName();
                    $object = $this->createObject($className, $params);
                    $resultParameters[] = $object;
                }
            }
        } catch (Exception $e)
        {
        }

        return array_values(array_merge($resultParameters, $params));
    }

    private function createObject(string $className, array $params = [])
    {
        $model = new $className();

        $data = array_merge($this->request->getQuery(), $params);

        foreach ($data as $name => $value)
            if (property_exists($model, $name))
                $model->{$name} = $value;

        return $model;
    }
}