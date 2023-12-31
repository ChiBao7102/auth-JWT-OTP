<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\VerifyOTPRequest;
use App\Services\User\UserService;
use Illuminate\Support\Carbon;
use App\Traits\ApiResponse;
use App\Mail\SendOTPCode;

class VerifyOTPController extends Controller
{
    use ApiResponse;
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function verifyOTPCode(VerifyOTPRequest $request){
        $user = $this->userService->getUserByEmailOTP($request->all());
        if (!$user) {
            return $this->error(null, 'User doesn\'t exist', 400);
        }
        if (Carbon::now()->gt($user->expired_confirm_code)) {
            return $this->error(null, 'Your OTP expired', 400);
        } else {
            if ($request->confirm_code != $user->confirm_code) {
                return $this->error(null, 'Your OTP is invalid', 400);
            }
            $user->confirm_status = true;
            $user->save();
            return $this->success($user, 'Successfully verified', 200);
        }
    }

    public function registerOTP(Request $request){
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = $this->userService->getUserByEmail($request->email);
        if($user){
            if($user['confirm_status']){
                return $this->error(null, 'Your email was verified', 401);
            }else {
                $user->confirm_code = random_int(100000, 999999);
                $user->expired_confirm_code = Carbon::now()->addSecond(60);
                $this->userService->update($user->id, $user->toArray());
                \Mail::to($user->email)->send(new SendOTPCode($user));
                return $this->success(null, 'Registered again,verify your email address to register', 200);
            }
        }else{
            return $this->error(null, 'User doesn\'t exist', 400);
        }
    }

}
