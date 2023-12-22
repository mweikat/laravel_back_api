<?php

declare(strict_types = 1);

namespace Src\admin\user\domain;

use Src\shared\domain\object\UserId;

interface UserRepository
{
    public function store(User $user): void;

    //public function getUserByEmail(UserEmail $email): User;
}
