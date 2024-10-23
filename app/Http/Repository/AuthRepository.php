<?php

namespace App\Http\Repository;

use App\Events\Sentotpevent;
use App\Http\Repository\Contracts\AuthRepositoryInterface;
use App\Jobs\RegisterProcessJob;
use App\Mail\Sendotpmail;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Models\ForgotPassword;
use App\Events\ForgotPasswordEvent;
use App\Jobs\AdminRegisterProcessJob;
use App\Jobs\ProcessFirstBooking;
use App\Jobs\ProcessFirstClinicBooking;
use App\Jobs\ProcessSecondBooking;
use App\Jobs\ProcessSecondClinicBooking;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
class AuthRepository implements AuthRepositoryInterface
{

    public function register($request){
        $user_type = 'user';
        RegisterProcessJob::dispatch($request->firstname, $request->lastname, $request->email, $request->password, $request->is_accepted,  $user_type,  $request->captcha);
        return response()->json(['success'=>'You have successfully registered, please check your email']);
    }

    public function adminregister($request){
        $user_type = "admin";
        // "stephen", "okpeku", "stephen@conscientiamd.com", "Jason007@"
        AdminRegisterProcessJob::dispatchSync($request->firstname, $request->lastname, $request->email, $request->password, 1,  $user_type,  25);
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
      $emailcheck =optional(User::where('email', $request->email))->first();

    $credentials = $request->only('email', 'password');

    if ( $emailcheck &&  Auth::attempt($credentials) ) {

        if ($emailcheck->confirm_status != 1) {
            return response()->json(['error' => "Your email is not confirmed."], 403);
        }

        $token = $emailcheck->createToken('auth-token')->plainTextToken;
        $otp = $this->generateotpcode();
        $emailcheck->update([
            'api_token' => $token,
            'otp' => $otp,
            'otp_status' => 'active',
        ]);

        $data = [
            'token' => $token,
            'email' => $emailcheck->email,
            'role' => $emailcheck->user_type,
            'firstname'=>$emailcheck->firstname,
            'lastname'=>$emailcheck->lastname,
            'id' => $emailcheck->id,
            'otp'=>$emailcheck->otp,
            'otp_status'=>$emailcheck->otp_status
        ];
        // Sentotpevent::dispatch($otp, $emailcheck->email);
        return response()->json([
            'success' => $data,
            'message' => 'Your have logged in successfully'
        ], 200);

    }else{
        return response()->json(['error' => "Your email is not confirmed."], 403);

    }
    }


    public function adminlogin($request){

        $emailcheck =optional(User::where('email', $request->email))->first();

        // if ($emailcheck->user_type != 'admin' || $emailcheck->user_type != 'customer_care'  || $emailcheck->user_type != 'super_admin' ) {
        //     return response()->json(['error' => "you don't have access"], 200);
        // }

        if ($emailcheck->user_type != 'admin' && $emailcheck->user_type != 'customer_care' && $emailcheck->user_type != 'super_admin') {
            return response()->json(['error' => "you don't have access"], 200);
        }

        $credentials = $request->only('email', 'password');
        if ( $emailcheck &&  Auth::attempt($credentials) ) {

            if ($emailcheck->confirm_status != 1) {
                return response()->json(['error' => "Your email is not confirmed."], 200);
            }

            $token = $emailcheck->createToken('auth-token')->plainTextToken;
            $otp = $this->generateotpcode();
            $emailcheck->update([
                'api_token' => $token,
                'otp' => $otp,
                'otp_status' => 'active',
            ]);

            $data = [
                'token' => $token,
                'email' => $emailcheck->email,
                'role' => $emailcheck->user_type,
                'firstname'=>$emailcheck->firstname,
                'lastname'=>$emailcheck->lastname,
                'id' => $emailcheck->id,
                'otp'=>$emailcheck->otp,
                'otp_status'=>$emailcheck->otp_status,
            ];
            // Sentotpevent::dispatch($otp, $emailcheck->email);
            return response()->json([
                'success' => $data,
                'message' => 'Your have logged in successfully'
            ], 200);

        }else{
            return response()->json(['error' => "Your email is not confirmed."], 200);

        }
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

    public function forget_password($request)
    {
        $user = User::where('email', $request->email)->first();
        if($user){

             $forgot =   ForgotPassword::where(["user_id"=>$user->id, 'is_used'=>0])->first();
             if($forgot){
                return response()->json([
                    'error' => 'A password reset request is already pending. Please check your email.'
                ], 200);
             }

             if(!$forgot){
                $generatecode = sha1(time());
                $forget =  ForgotPassword::create([
                     'user_id'=>$user->id,
                     'code'=>$generatecode,
                     'is_used'=>0
                 ]);
                 event(new ForgotPasswordEvent($forget->code, $user->email));
                 return response()->json(['success'=>'please check your email to reset your password'],200);
             }

        }else{
            return response()->json(['error'=>'your email does not exist second'],200);

        }
    }


    public function reset_password($request){
        $user = User::where('email', $request->email)->first();
        if($user){
            $forgot =   ForgotPassword::where(["user_id"=>$user->id, 'code'=>$request->code, 'is_used'=>0])->first();
            if($forgot){
                $forgot->update([
                  "is_used"=>1
                ]);
                $user->update([
                    'password'=>Hash::make($request->password)
                ]);
                return response()->json(['success'=>"your password has been change"],200);
            }else{
                return response()->json(['error'=>"this code has been used"],200);

            }

        }else{
            return response()->json(['error'=>'something went wrong'],200);
        }

    }


    public function online_booking($request){
         $user = User::where('email', $request->email)->first();
         if(!$user){
            $generatecode = sha1(time());
           ProcessFirstBooking::dispatch(
            $request->firstname,
            $request->lastname,
            $request->state,
            $request->doctor,
            $request->email,
            $request->phone,
            $request->comment,
            $request->visit_type,
            $generatecode,
            $request->captcha,
            $request->country,
            $request->legal_sex,
            $request->dob,
            $request->schedule_time,
            $request->mean_payment
           );

           return response()->json(['success'=>"Please check your email"], 200);
         }else{
            $generatecode = sha1(time());
                  ProcessSecondBooking::dispatch(
                    $request->firstname,
                    $request->lastname,
                    $request->state,
                    $request->doctor,
                    $request->email,
                    $request->phone,
                    $request->comment,
                    $request->visit_type,
                    $generatecode,
                    $request->captcha,
                    $request->country,
                    $request->legal_sex,
                    $request->dob,
                    $request->schedule_time,
                    $request->mean_payment
                  );

                  return response()->json(['success'=>'thank you for booking an Online appointment we will get back to you soon'],200);
         }
    }


    public function clinic_booking($request){
        $user = User::where('email', $request->email)->first();
        if(!$user){
            $generatecode = sha1(time());
              ProcessFirstClinicBooking::dispatch(
                $request->firstname,
                $request->lastname,
                $request->state,
                $request->doctor,
                $request->email,
                $request->phone,
                $request->comment,
                $request->visit_type,
                $generatecode,
                $request->captcha,
                $request->country,
                $request->legal_sex,
                $request->dob,
                $request->schedule_time,
                $request->mean_payment
              );
              return response()->json(['success'=>"Please check you eamil"], 200);
        }else{
            $generatecode = sha1(time());
              ProcessSecondClinicBooking::dispatch(
                $request->firstname,
                $request->lastname,
                $request->state,
                $request->doctor,
                $request->email,
                $request->phone,
                $request->comment,
                $request->visit_type,
                $generatecode,
                $request->captcha,
                $request->country,
                $request->legal_sex,
                $request->dob,
                $request->schedule_time,
                $request->mean_payment
              );
              return response()->json(['success'=>'thank you for booking a Clinical appointment we will get back to you soon'],200);

        }
    }



    public function set_password($request){
        $user = User::where('email', $request->email)->first();
        if($user){
            $forgot =   Booking::where(['code'=>$request->code, 'is_used'=>'active'])->first();
            if($forgot){
                $forgot->update([
                  "is_used"=>'used'
                ]);
                $user->update([
                    'password'=>Hash::make($request->password)
                ]);
                return response()->json(['success'=>"your password has been change"],200);
            }else{
                return response()->json(['error'=>"this code has been used"],200);

            }

        }else{
            return response()->json(['error'=>'something went wrong'],200);
        }

    }

}




