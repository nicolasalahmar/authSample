<?php

use App\Http\Controllers\PassportAuth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/login', [PassportAuth\LoginController::class,'login'])->name('login.api');
    Route::post('/refreshtoken', [PassportAuth\LoginController::class,'refreshToken'])->name('refreshToken.api');
    Route::post('/resetpassword', [PassportAuth\ForgotPasswordController::class,'sendResetLinkEmail'])->name('resetpassword.api');
    Route::post('/register', [PassportAuth\RegisterController::class,'register'])->name('register.api');
});


Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [PassportAuth\LoginController::class,'logout'])->name('logout.api');
    Route::get('/resendverification', [PassportAuth\VerificationController::class,'resend'])->name('resend.api');
});
