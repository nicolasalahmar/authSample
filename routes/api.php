<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/login', [Api_Auth\LoginController::class,'login'])->name('login.api');
    Route::post('/register', [Api_Auth\RegisterController::class,'register'])->name('register.api');
    //Route::get('/email/verify/{id}/{hash}', [Api_Auth\VerificationController::class,'verify'])->name('verify.api');
});


Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [Api_Auth\LoginController::class,'logout'])->name('logout.api');
});*/
