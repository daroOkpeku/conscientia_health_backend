<?php


namespace App\Http\Repository\Contracts;


interface AuthRepositoryInterface{


    public function register($request);
    // public function verify_email($request);
    public function login($request);
    public function otp($request);
    public function resendotp($request);
    public function forget_password($request);
    public function reset_password($request);
    public function online_booking($request);
    public function clinic_booking($request);
    public function set_password($request);
}
