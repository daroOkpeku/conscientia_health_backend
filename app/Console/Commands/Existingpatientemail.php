<?php

namespace App\Console\Commands;

use App\Events\ExistinguserEmailEvent;
use App\Models\ForgotPassword;
use App\Models\User;
use Illuminate\Console\Command;

class Existingpatientemail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'existing:patientemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::where("is_existed_new", 0)->get();

        foreach ($users as $user) {
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
                     ExistinguserEmailEvent::dispatch($forget->code, $user->email);
                    // return response()->json(['success'=>'please check your email to reset your password'],200);
                }

           }else{
               return response()->json(['error'=>'your email does not exist second'],200);

           }
        }




    }
}
