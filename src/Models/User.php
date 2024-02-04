<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class User extends Model
{
    public $id;

    public $name;

    public $userName;

    public $email;

    public $password;

    public $roleId;

    public $active;

    public function initialize()
    {
        $this->setSource('user');
    }

    public function columnMap()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'user_name' => 'userName',
            'email' => 'email',
            'password' => 'password',
            'role_id' => 'roleId',
            'active' => 'active'
        ];
    }
}