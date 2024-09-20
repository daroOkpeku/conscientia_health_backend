<?php

namespace App\Listeners;

use App\Events\BookingAdminEvent;
use App\Mail\BookingAdminMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class BookingAdminListener
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(BookingAdminEvent $event): void
    {
      
        $data = [
             'firstname'=>$event->firstname,
             'lastname'=>$event->lastname,
             "state"=>$event->state,
             "doctor"=> $event->doctor,
             "email"=>$event->email,
             "phone"=>$event->phone,
             "visit_type"=>$event->visit_type,
             'comment'=>$event->comment
        ];

        Mail::to($event->email)->send(new BookingAdminMail($data));
    }
}
