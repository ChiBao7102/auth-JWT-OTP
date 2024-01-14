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
use App\Mail\ResetPasswordMail;
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

    public function forgotPassword(Request $request){
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $data = [
            'email' => $request->email,
            'exp' => Carbon::now()->addSecond(300)
        ];
        $token = base64_encode(json_encode($data));
        $hostwithHttp = request()->getSchemeAndHttpHost();
        Mail::to($request->email)->send(new ResetPasswordMail($hostwithHttp."/api/reset-password/".$token));
        return $this->success(null,'Successfully sent email reset password',200);
    }

    public function resetPassword(Request $request, $token){
        $data = (array)json_decode(base64_decode($token));
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);
        $user = $this->userService->getUserByEmail($data['email']);
        if (!$user || !$user->confirm_status) {
            return $this->error(null,'User not found', 404);
        }
        if (Carbon::now()->gt($data['exp'])) {
            return $this->error(null, 'Password change request has expired', 400);
        }
        $user->password = Hash::make($request->password);
        $this->userService->update($user->id, $user->getAttributes());
        return $this->success(null,'Successfully reset your password',200);
    }

    public function getInfo()
    {
        $user = Auth::user();
        Mail::to($user->email)->send(new testSendMail($user));
        return $this->success($user,'Successfully logged info',200);
    }
}
