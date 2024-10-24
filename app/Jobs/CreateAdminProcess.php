<?php

namespace App\Jobs;

use App\Events\ExistinguserEmailEvent;
use App\Models\ForgotPassword;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateAdminProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $firstname;
    public $lastname;
    public $state;
    public $doctor;
    public $email;
    public $gender;
    public $dob;
    public $user_type;
    /**
     * Create a new job instance.
     */
    public function __construct($firstname, $lastname, $state, $doctor, $email, $gender, $dob, $user_type)
    {
     $this->firstname = $firstname;
     $this->lastname = $lastname;
     $this->state = $state;
     $this->doctor = $doctor;
     $this->email = $email;
     $this->gender = $gender;
     $this->dob = $dob;
     $this->user_type = $user_type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function(){
          $user =  User::create([
                'firstname'=>$this->firstname,
                'lastname'=>$this->lastname,
                'email'=>$this->email,
                'otp_status'=>'active',
                'user_type'=>$this->user_type,
                'captcha'=>27,
                'is_accepted'=>1,
                'confirm_status'=>1,
                'is_existed_new'=>1,
            ]);
            Profile::create([
                "first_name"=>$user->firstname,
                "last_name"=>$user->lastname,
                "email"=>$user->email,
                "state"=>$this->state,
                "doctor"=>$this->doctor,
                "date_of_birth"=>$this->dob,
                "gender"=>$this->gender,
                'user_id'=>$user->id
            ]);

            $generatecode = sha1(time());
            $forget =  ForgotPassword::create([
                 'user_id'=>$user->id,
                 'code'=>$generatecode,
                 'is_used'=>0
             ]);
              ExistinguserEmailEvent::dispatch($forget->code, $user->email);

        });
    }
}
