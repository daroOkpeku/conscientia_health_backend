<?php

namespace App\Listeners;

use App\Events\BookingEvent;
use App\Mail\SendBooking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class BookingListener
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
    public function handle(BookingEvent $event): void
    {
        $data = [
            "email"=>$event->email,
            "code"=>$event->code
        ];

        Mail::to($event->email)->send(new SendBooking($data));
    }
}
