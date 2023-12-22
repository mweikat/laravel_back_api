<?php

declare(strict_types=1);

namespace Src\admin\user\infrastructure\controllers;

use Src\admin\user\infrastructure\persistence\EloquentUserRepository;
use Src\admin\user\application\Register;

final class UserController
{

    public function store(string $id, string $name, string $lastName, string $email, string $password) {

      return (new Register( new EloquentUserRepository(), $id, $name, $lastName, $email, $password))();

    }

}