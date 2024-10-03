<?php

namespace App\Http\Controllers;

use App\Events\ForgotPasswordEvent;
use App\Http\Repository\Contracts\AuthRepositoryInterface;
use App\Http\Requests\ForgotRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\Online_booking_request;
use App\Http\Requests\OTPrequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\Resetrequest;
use App\Models\User;
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

    public function forgot_password(ForgotRequest $request){
     return $this->authinterface->forget_password($request);
    }

    public function reset_password(Resetrequest $request){
      return $this->authinterface->reset_password($request);
    }

    public function online_booking(Online_booking_request $request){
        return $this->authinterface->online_booking($request);
    }

    public function clinic_booking(Online_booking_request $request){
      return $this->authinterface->clinic_booking($request);
    }

    public function set_password(Resetrequest $request){
        return $this->authinterface->set_password($request);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json(['success'=>'logged out']);
     }
}
