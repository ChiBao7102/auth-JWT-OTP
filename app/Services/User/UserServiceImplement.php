<?php

namespace App\Services\User;

use LaravelEasyRepository\Service;
use App\Repositories\User\UserRepository;

class UserServiceImplement extends Service implements UserService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(UserRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function getAllImplement(){
        return $this->mainRepository->getAll();
    }

    public function deleteUser($id){
        return $this->mainRepository->findOrFail($id)->delete();
    }
}
