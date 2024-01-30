<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Repository;

interface UserRepository extends Repository
{

    public function getAll();
    public function getUserByEmailOTP($data);
    public function getUserByEmail($data);
}
