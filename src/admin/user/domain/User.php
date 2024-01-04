<?php

declare(strict_types = 1);

namespace Src\admin\user\domain;

use Src\shared\domain\IConvert;


class User implements IConvert, \JsonSerializable
{
    private $id;
    private $name;
    private $lastName;
    private $email;
    //private $email_verified_at;
    private $password;
    
    public function __construct(string $id, string $name, string $lastName=null, string $email, string $password){

        $this->id = $id;
        $this->name = $name;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
    }

    public function id(){
        return $this->id;
    }
    
    public function name(){
        return $this->name;
    }

    public function lastName(){
        return $this->lastName;
    }

    public function email(){
        return $this->email;
    }

    public function password(){
        return $this->password;
    }

    public function toArray():array{
        return  array(
                    'id' => $this->id(),
                    'name'=>$this->name(),
                    'lastName'=>$this->lastName(),
                    'email'=>$this->email(),
                    );
    }

    public function jsonSerialize() {
        return $this->toArray();
    }
    
}