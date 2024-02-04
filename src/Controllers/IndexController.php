<?php

namespace App\Controllers;

use App\Models\User;
use App\UseCases\GetUser;
use App\UseCases\GetUsers;
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction(GetUsers $request)
    {
        echo 'index - ' . get_class($request) . PHP_EOL . PHP_EOL;

        print_r($request);

        $users = $this->modelsManager->createBuilder()
            ->from(User::class)
            ->where('name like :name:', ['name' => '%'.$request->name.'%'])
            ->getQuery()
            ->execute();

        print_r($users->toArray());
        die();
    }

    public function findAction(User $user)
    {
        print_r($user->toArray());
    }

    public function findFirstAction(GetUser $request, $id)
    {
        $user = User::findFirst($request->userId);
        print_r($user->toArray());
    }
}