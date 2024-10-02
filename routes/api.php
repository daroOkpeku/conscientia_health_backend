<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DrChronoWebhookController;
use App\Http\Controllers\GetController;
use App\Http\Controllers\PostController;
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
Route::get("/auth/callback/drchrono", "drchrono");
Route::get("/auth/redirect/drchrono", "redirectdrchrono");
Route::get("/getAccessToken", "getAccessToken");
Route::get("/list_doctors", "list_doctors");
Route::get("/doctor_availiable", "doctor_availiable");
Route::get("/state_age_check", "state_age_check");
});
// https://app.drchrono.com/api/appointments

Route::controller(AuthController::class)->group(function(){
    Route::post('/register', 'register');
    Route::post("/login", "login");
    Route::post('/otp', 'otp');
    Route::get('/resendotp', 'resendotp')->where(['id' => '[0-9]+']);
    Route::post("/forgot_password", "forgot_password");
    Route::post('/reset_password', 'reset_password');
    Route::post("/online_booking", "online_booking");
    Route::post("/clinic_booking", "clinic_booking");
    Route::post("/set_password", "set_password");
});



Route::controller(DrChronoWebhookController::class)->group(function(){
    Route::post('/webhook/drchrono', 'webhook');
});




Route::controller(PostController::class)->group(function(){
    Route::post("/contact", "contact");

});

// profiles


Route::middleware('auth:sanctum')->group(function () {

    Route::controller(PostController::class)->group(function(){
        Route::post("/profile_create", "profile_create");
        Route::put("profile_edit", "profile_edit");
        Route::post("/uploadprofileimage", "uploadprofileimage");
        Route::put("change_password", "change_password");
        Route::post("primary_insurance", "primary_insurance");
        Route::put("primary_insurance_edit", "primary_insurance_edit");
        Route::post("secondary_insurance_create", "secondary_insurance_create");
        Route::put("secondary_insurance_edit", "secondary_insurance_edit");
        Route::post("employer_create", "employer_create");
        Route::put("employer_edit", "employer_edit");
        Route::post("responsible_party_create", "responsible_party_create");

    });

    Route::controller(GetController::class)->group(function(){
        Route::get("/get_profile/{editid}", "get_profile");
        Route::get("/primary_get/{user_id}", "primary_get");
        Route::get("/secondary_get/{user_id}", "secondary_get");
        Route::post("/uploadPicture", "uploadPicture");

    });

});
