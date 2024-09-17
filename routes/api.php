<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// gencaptcha


Route::controller(GetController::class)->group(function(){
Route::get('/gencaptcha', 'gencaptcha');
Route::get('/verify_email', 'verify_email')->where(['email' => '.*', 'firstname' => '[A-Za-z]+']);
Route::get('/decryptToken/{token}', 'decryptToken');
});


Route::controller(AuthController::class)->group(function(){
    Route::post('/register', 'register');
    Route::post("/login", "login");
    Route::post('/otp', 'otp');
    Route::get('/resendotp', 'resendotp')->where(['id' => '[0-9]+']);
});
