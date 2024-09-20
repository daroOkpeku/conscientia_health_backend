<?php

namespace App\Jobs;
use App\Events\BookingAdminEvent;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSecondClinicBooking implements ShouldQueue
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
        $booking = Booking::where('email', $this->email)->first();

        if(!$booking){
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
                "is_used"=>"used",
            ]);

             BookingAdminEvent::dispatch($this->firstname, $this->lastname, $this->state, $this->doctor, $this->email, $this->phone,
            $this->comment, $this->visit_type,  $booking->code,  $booking->is_used);
        }else{
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
                "is_used"=>"used",
            ]);

             BookingAdminEvent::dispatch($this->firstname, $this->lastname, $this->state, $this->doctor, $this->email, $this->phone,
            $this->comment, $this->visit_type,  $booking->code,  $booking->is_used);
        }
    }
}
