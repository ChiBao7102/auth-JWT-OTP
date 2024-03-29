<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\VerifyOTPController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
| Route::get('getUser', 'getInfo');
| ___________'name route'__' function '_;
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('forgot-password', 'forgotPassword');
    Route::put('reset-password/{token}', 'resetPassword');
});

Route::controller(VerifyOTPController::class)->group(function () {
    Route::post('verify-otp', 'verifyOTPCode');
    Route::post('register-otp', 'registerOTP');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('user')->group(function () {
        Route::get('/', [AuthController::class, 'getInfo']);
    });
});
