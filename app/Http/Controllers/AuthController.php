<?php

namespace App\Http\Controllers;
use App\Http\Repository\Contracts\AuthRepositoryInterface;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OTPrequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $authinterface;
    public function __construct(AuthRepositoryInterface $authinterface){
        $this->authinterface = $authinterface;
    }

    public function register(RegisterRequest $request){
     return  $this->authinterface->register($request);
    }


    public function login(LoginRequest $request){
        return $this->authinterface->login($request);

    }

    public function otp(OTPrequest $request){
       return $this->authinterface->otp($request);
    }
    
    public function resendotp(Request $request){
        return $this->authinterface->resendotp($request);
    }
    // public function verify_email(Request $request){
    //      $request->validate([
    //         'email' => 'required|email'
    //     ]);
    //     return $this->authinterface->verify_email($request);
    // }
}
