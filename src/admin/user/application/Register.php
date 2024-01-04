<?php

declare(strict_types=1);

namespace Src\admin\user\application;

use Src\admin\user\domain\UserRepository;
use Src\admin\user\domain\User;

final class Register{

    private $repository;
    private User $user;

    public function __construct(UserRepository $repository, string $id, 
                                                            string $name, 
                                                            string $lastName=null,
                                                            string $email,
                                                            string $password
                                                            )
    {
        $this->user = new User($id,$name,$lastName,$email,$password);
        
        $this->repository = $repository;
    }

    public function __invoke()
    {
        return $this->repository->store($this->user);
        
    }

}