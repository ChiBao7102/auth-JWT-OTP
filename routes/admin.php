<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
| Route::get('getAllUser', 'getAllUser');
| ___________'name route'__' function '_;
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    // Route::post('refresh', 'refresh');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/getAllUser', [AuthController::class, 'getAllUser']);
    Route::delete('/delete/{id}', [AuthController::class, 'deleteUser']);
});
