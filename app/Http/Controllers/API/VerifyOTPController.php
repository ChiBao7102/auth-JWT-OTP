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

    public function verifyOTPCode(VerifyOTPRequest $request)
    {
        $user = $this->userService->getUserByEmailOTP($request->all());
        if (!$user) {
            return $this->error(null, config('constants.request_OTP.user_not_found'), 400);
        }
        if (Carbon::now()->gt($user->expired_confirm_code)) {
            return $this->error(null, config('constants.request_OTP.code_expired'), 400);
        } else {
            if ($request->confirm_code != $user->confirm_code) {
                return $this->error(null, config('constants.request_OTP.code_invalid'), 400);
            }
            $user->confirm_status = true;
            $user->save();
            return $this->success($user, config('constants.request_OTP.success_verify'), 200);
        }
    }

    public function registerOTP(Request $request)
    {
        $request->validate(
            [
            'email' => 'required|string|email',
            ]
        );
        $user = $this->userService->getUserByEmail($request->email);
        if($user) {
            if($user['confirm_status']) {
                return $this->error(null, config('constants.request_OTP.user_was_verified'), 401);
            }else {
                $user->confirm_code = random_int(100000, 999999);
                $user->expired_confirm_code = Carbon::now()->addSecond(120);
                $this->userService->update($user->id, $user->getAttributes());
                \Mail::to($user->email)->send(new SendOTPCode($user));
                return $this->success(null, config('constants.request_OTP.register_otp_success'), 200);
            }
        }else{
            return $this->error(null, config('constants.request_OTP.user_not_found'), 400);
        }
    }

}
