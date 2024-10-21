<?php

namespace App\Jobs;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
class AdminRegisterProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $is_accepted;
    public $user_type;
    public $captcha;
    /**
     * Create a new job instance.
     */
    public function __construct($firstname, $lastname, $email, $password, $is_accepted,   $user_type, $captcha)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        $this->is_accepted = $is_accepted;
        $this->user_type = $user_type;
        $this->captcha = $captcha;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::create([
            'firstname'=>$this->firstname,
            'lastname'=>$this->lastname,
            'email'=>$this->email,
            'user_type'=>$this->user_type,
            'confirm_status'=>1,
            'is_existed_new'=>1,
            'captcha'=>$this->captcha,
            'is_accepted'=>$this->is_accepted,
            'password'=>Hash::make($this->password),
        ]);
    }
}
