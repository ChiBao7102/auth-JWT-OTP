<?php

namespace App\Services\User;

use LaravelEasyRepository\BaseService;

interface UserService extends BaseService{

    public function getAllImplement();
    public function deleteUser($id);
    public function getUserByEmailOTP($data);
}
