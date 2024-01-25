<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\User\UserService;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('admin')->attempt($credentials);
        if (!$token) {
            return $this->error(null,config('constants.auth.HTTP_UNAUTHORIZED'), 401);
        }

        $user = Auth::guard('admin')->user();
        return $this->success([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ],config('constants.auth.HTTP_LOGIN_SUCCESS'), 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = Admin::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->success($user, config('constants.auth.HTTP_REGISTER_SUCCESS'), 200);
    }

    public function logout()
    {
        Auth::logout();
        return $this->success(null, config('constants.auth.HTTP_LOGOUT_SUCCESS'), 200);
    }

    public function getAllUser(){
        $user = $this->userService->getAllImplement();
        return $this->success($user, config('constants.user.get_all_info_success'), 200);
    }

    public function deleteUser($id){
        $user = $this->userService->deleteUser($id);
        return $this->success($user, config('constants.user.delete_success'), 200);
    }
}
