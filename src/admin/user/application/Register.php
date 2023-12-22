<?php

declare(strict_types=1);

namespace Src\admin\user\application;

use Src\admin\user\domain\UserRepository;
use Src\admin\user\domain\UserPassword;
use Src\admin\user\domain\UserLastName;
use Src\admin\user\domain\UserEmail;
use Src\admin\user\domain\UserName;
use Src\shared\domain\object\UserId;
use Src\admin\user\domain\User;

final class Register{

    private $repository;
    private User $user;

    public function __construct(UserRepository $repository, string $id, 
                                                            string $name, 
                                                            string $lastName,
                                                            string $email,
                                                            string $password
                                                            )
    {
        $this->user = new User(new UserId($id),
                               new UserName ($name),
                               new UserLastName ($lastName),
                               new UserEmail($email),
                               new UserPassword ($password)
                               );
        
        $this->repository = $repository;
    }

    public function __invoke()
    {
        return $this->repository->store($this->user);
        
    }

}