<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\testSendMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Services\User\UserService;
use App\Mail\SendOTPCode;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;
    protected UserService $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $credentials['confirm_status'] = 1;
        $token = Auth::attempt($credentials);
        if (!$token) {
            return $this->error(null,'Unauthorized', 401);
        }
        $user = Auth::user();
        return $this->success([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ],'Unauthorized', 200);
    }

    public function register(RegisterRequest $request)
    {
        $this->userService->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'confirm_code' => random_int(100000, 999999),
            'expired_confirm_code' => Carbon::now()->addSecond(120),
            'expired_register_in' => Carbon::now(),
        ]);
        $user = $this->userService->getUserByEmail($request->email);
        Mail::to($user->email)->send(new SendOTPCode($user));
        return $this->success($user, 'User created successfully', 200);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function getInfo()
    {
        $user = Auth::user();
        Mail::to($user->email)->send(new testSendMail($user));
        return $this->success($user,'Successfully logged info',200);
    }
}
