<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Crypt;
class GetController extends Controller
{
    public function gencaptcha(){
       
        return response()->json(['image'=>Captcha::src()]);
    }

    public function verify_email(Request $request){
     $user = User::where(['email'=>$request->get('email'), 'firstname'=>$request->get('firstname')])->first();
     if($user){
      $user->update([
        'confirm_status'=>1
      ]);
      return response()->json(['success'=>'Your email has been confirmed', 'status'=>true], 200);
     }else{
        return response()->json(['error'=>'Email not Found', 'status'=>false], 404);
     }
    }


    public function decryptToken ($token) {

        return  response()->json(["success"=>Crypt::decrypt($token)]);

    }
}
