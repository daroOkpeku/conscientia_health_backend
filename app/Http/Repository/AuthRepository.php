<?php

namespace App\Http\Repository;

use App\Events\Sentotpevent;
use App\Http\Repository\Contracts\AuthRepositoryInterface;
use App\Jobs\RegisterProcessJob;
use App\Mail\Sendotpmail;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{

    public function register($request){
        $user_type = 'user';
        RegisterProcessJob::dispatch($request->firstname, $request->lastname, $request->email, $request->password, $request->is_accepted,  $user_type,  $request->captcha);
        return response()->json(['success'=>'You have successfully registered, please check your email']);
    }

    public function generateotpcode(){
        $randnum = rand(00000, 99999);
        $time = now();


        $randnum_first_three = substr($randnum, 0, 3);
        $time_seconds = $time->format('s');
        $time_first_three = substr($time_seconds, 0, 3);


        $joined_value = $randnum_first_three . $time_first_three;
        return $joined_value;
    }

    public function login($request){
      $emailcheck = User::where('email', $request->email)->first();

      if (!$emailcheck || !Hash::check($request->password, $emailcheck->password)) {
        return response()->json(['error' => "Incorrect email or password."], 401);
    }
    
    if ($emailcheck->confirm_status != 1) {
        return response()->json(['error' => "Your email is not confirmed."], 403);
    }

    $token = $emailcheck->createToken('auth-token')->plainTextToken;
    $otp = $this->generateotpcode();
    $emailcheck->update([
        'api_token' => $token,
        'otp' => $otp,
        'otp_status' => 'nothing'
    ]);

    $data = [
        'token' => $token,
        'email' => $emailcheck->email,
        'role' => $emailcheck->user_type,
        'id' => $emailcheck->id,
        'otp'=>$emailcheck->otp,
        'otp_status'=>$emailcheck->otp_status
    ];
    Sentotpevent::dispatch($otp, $emailcheck->email);
    return response()->json([
        'success' => $data,
        'message' => 'Please check your email for the OTP code.'
    ], 200);

    }



    public function otp($request){
      
     $user =  User::where(["id"=>$request->id, 'otp'=>$request->otp])->first();
     if($user){
        $user->update([
         'otp_status'=>'active'
        ]);
        $data = [
            'token' => $user->token,
            'email' => $user->email,
            'role' => $user->user_type,
            'id' => $user->id,
            'otp'=>$user->otp,
            'otp_status'=>$user->otp_status
        ];
        return response()->json([
            'success' => $data,
            'message' => 'Your Login is Successful.'
        ], 200);
     }
    }

    public function resendotp($request){
        $user =  User::find($request->get('id'));  
        if($user){
            $otp = $this->generateotpcode();
            $user->update([
                'otp' => $otp,
                'otp_status' => 'nothing'
            ]);
            Sentotpevent::dispatch($otp, $user->email);
            return response()->json([
                'success' => 'Please check your email for the OTP code.'
            ], 200);
        }
    }


}
