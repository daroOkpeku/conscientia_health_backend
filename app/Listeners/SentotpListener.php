<?php

namespace App\Listeners;

use App\Events\Sentotpevent;
use App\Mail\Sendotpmail;
use App\Mail\SendRegisteremail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SentotpListener
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
    public function handle(Sentotpevent $event): void
    {
        $data = [
            'otp'=>$event->otp,
            'email'=>$event->email
          ];

          Mail::to($event->email)->send(new Sendotpmail($data));
    }
}
