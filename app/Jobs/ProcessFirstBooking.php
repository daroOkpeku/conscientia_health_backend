<?php

namespace App\Jobs;

use App\Events\BookingEvent;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessFirstBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public  $firstname;
    public $lastname;
    public $state;
    public $doctor;
    public $email;
    public  $phone;
    public $comment;
    public $visit_type;
    public $generatecode;
    public $captcha;
    /**
     * Create a new job instance.
     */
    public function __construct(
        $firstname,
        $lastname,
        $state,
        $doctor,
        $email,
        $phone,
        $comment,
        $visit_type,
        $generatecode,
        $captcha,
    )
    {
     
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->state = $state;
        $this->doctor = $doctor;
        $this->email = $email;
        $this->phone = $phone;
        $this->comment = $comment;
        $this->visit_type = $visit_type;
        $this->generatecode = $generatecode;
        $this->captcha = $captcha;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        DB::transaction(function () {
          $booking =  Booking::create([
                'firstname'=>$this->firstname,
                'lastname'=>$this->lastname,
                'state'=>$this->state,
                'doctor'=>$this->doctor,
                'email'=>$this->email,
                'phone'=>$this->phone,
                'comment'=>$this->comment,
                "visit_type"=>$this->visit_type,
                "code"=>$this->generatecode,
                "is_used"=>"active",
            ]);
            User::create([
                'firstname'=>$this->firstname,
                'lastname'=>$this->lastname,
                'email'=>$this->email,
                "is_accepted"=>1,
                "user_type"=>'user',
                'captcha'=>$this->captcha,
                'confirm_status'=>1
            ]);
                event( new BookingEvent( $booking->email, $booking->code) );
            
        });
        
   
    }
}
