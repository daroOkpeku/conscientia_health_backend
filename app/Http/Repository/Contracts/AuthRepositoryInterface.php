<?php


namespace App\Http\Repository\Contracts;


interface AuthRepositoryInterface{


    public function register($request);
    // public function verify_email($request);
    public function login($request);
    public function otp($request);
    public function resendotp($request);
}
