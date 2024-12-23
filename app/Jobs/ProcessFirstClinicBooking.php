<?php

namespace App\Jobs;
use App\Events\BookingEvent;
use App\Events\BookingAdminEvent;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessFirstClinicBooking implements ShouldQueue
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
    public  $country;
    public  $legal_sex;
    public $dob;
    public $schedule_time;
    public $mean_payment;

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
        $country,
        $legal_sex,
        $dob,
        $schedule_time,
        $mean_payment
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
        $this->country = $country;
        $this->legal_sex = $legal_sex;
        $this->dob = $dob;
        $this->schedule_time = $schedule_time;
        $this->mean_payment = $mean_payment;
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
                  "country"=>$this->country,
                  "legal_sex"=>$this->legal_sex,
                  "dob"=>$this->dob,
                  "schedule_time"=>$this->schedule_time,
                  "mean_payment"=>$this->mean_payment
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
              event( new BookingAdminEvent($this->firstname, $this->lastname, $this->state, $this->doctor, $this->email, $this->phone,
                  $this->comment, $this->visit_type,  $booking->code,  $booking->is_used,   $this->schedule_time, $this->mean_payment, $this->country));

          });
    }
}
