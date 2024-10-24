<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;

class CreateAdminEditProcess implements ShouldQueue
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
    public $id;
    public $user_id;
    /**
     * Create a new job instance.
     */
    public function __construct($firstname, $lastname, $state, $doctor, $email, $gender, $dob, $user_type, $id, $user_id)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->state = $state;
        $this->doctor = $doctor;
        $this->email = $email;
        $this->gender = $gender;
        $this->dob = $dob;
        $this->user_type = $user_type;
        $this->id = $id;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        DB::transaction(function () {
           $user = User::find($this->user_id);

           $user->update([
            'firstname'=>$this->firstname,
            'lastname'=>$this->lastname,
            'email'=>$this->email,
            'user_type'=>$this->user_type,
           ]);

           Profile::where(["user_id"=>$user->id])->update([
            "first_name"=>$user->firstname,
            "last_name"=>$user->lastname,
            "email"=>$user->email,
            "state"=>$this->state,
            "doctor"=>$this->doctor,
            "date_of_birth"=>$this->dob,
            "gender"=>$this->gender,
            //'user_id'=>$user->id
        ]);
        });

    }
}
