<?php

namespace App\Listeners;

use App\Events\Contactevent;
use App\Mail\SendContactMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class Contactlistener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Contactevent $event): void
    {
        $data = [
          "name"=>$event->name,
           "email"=>$event->email,
           "phone"=>$event->phone,
           "subject"=>$event->subject,
           "comment"=>$event->comment
        ];

        Mail::to($event->companyemail)->send( new SendContactMail($data) );
    }
}
