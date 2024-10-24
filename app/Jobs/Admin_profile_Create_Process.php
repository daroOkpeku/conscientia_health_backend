<?php

namespace App\Jobs;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class Admin_profile_Create_Process implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    // "first_name"=>$request->first_name,
    // "last_name"=>$request->last_name,
    // "email"=>$request->email,
    // "state"=>$request->state,
    // "home_phone"=>$request->home_phone,
    // "office_phone"=>$request->office_phone,
    // "cell_phone"=>$request->cell_phone,
    // "gender"=>$request->gender,
    // "id"=>$request->id
    // public $first_name;
    // public $last_name;
    // public $email;
    // public $state;
    // public $home_phone;
    // public $office_phone;
    // public $cell_phone;
    // public $gender;
    // public $id;
    public $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $user = User::find($this->data["id"]);

            $user->update([
             'firstname'=>$this->data['first_name'],
             'lastname'=>$this->data['last_name'],
             'email'=>$this->data['email'],
            ]);


            Profile::where(["user_id"=>$user->id])->update([
             "first_name"=>$user->firstname,
             "last_name"=>$user->lastname,
             "email"=>$user->email,
             "state"=>$this->data['state'],
             "home_phone"=>$this->data['home_phone'],
             "office_phone"=>$this->data['office_phone'],
             "cell_phone"=>$this->data['cell_phone'],
             "gender"=>$this->data['gender'],
             //'user_id'=>$user->id
         ]);
         });
    }
}
