<?php

declare(strict_types = 1);

namespace Src\admin\user\infrastructure\persistence;

use Src\shared\domain\genericErrors\CreationError;
use Src\shared\domain\genericErrors\NotFound;
use Src\admin\user\domain\UserRepository;
use Src\admin\user\domain\UserPassword;
use Src\admin\user\domain\UserLastName;
use Src\admin\user\domain\UserEmail;
use Src\shared\domain\object\UserId;
use Src\admin\user\domain\UserName;
use Src\admin\user\domain\User;
use Log;

use App\Models\User as EloquentUser;

final class EloquentUserRepository implements UserRepository{

    public function store(User $user): void
    {
        try{

            $model           = new EloquentUser();
            $model->id       = $user->id()->value();
            $model->name     = $user->name()->value();
            $model->lastName = $user->lastName()->value();
            $model->email = $user->email()->value();
            $model->password = $user->password()->value();
            

            $model->save();

            $model->sendEmailVerificationNotification();


        }catch(\Exception $e){
            Log::error('EloquentUserRepository->store: '.$e->getMessage());
            throw new CreationError($user);
        }
        
    }

   

   
}
