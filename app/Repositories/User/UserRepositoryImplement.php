<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User;

class UserRepositoryImplement extends Eloquent implements UserRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAll(){
        return $this->model->all();
    }

    public function getUserByEmailOTP($data){
        return $this->model->where('email','=',$data['email'])->where('confirm_code','=',$data['confirm_code'])->first();
    }

    public function getUserByEmail($data){
        return $this->model->where('email','=',$data)->first();
    }
}
