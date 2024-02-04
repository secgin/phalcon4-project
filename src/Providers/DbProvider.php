<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class DbProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $dbConfig = $di->get('config')->database;

        $di->setShared('db', function () use ($dbConfig){
            $class = 'Phalcon\Db\Adapter\Pdo\\' . $dbConfig->adapter;
            $params = [
                'host' => $dbConfig->host,
                'username' => $dbConfig->username,
                'password' => $dbConfig->password,
                'dbname' => $dbConfig->dbname,
                'charset' => $dbConfig->charset,
                'port' => $dbConfig->port
            ];

            return new $class($params);
        });
    }
}