<?php

namespace App\Listeners;

use App\Events\ForgotPasswordEvent;
use App\Mail\SendForgetMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordListener
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
    public function handle(ForgotPasswordEvent $event): void
    {
        $data = [
            "code"=>$event->code,
            "email"=>$event->email
        ];
        Mail::to($event->email)->send(new SendForgetMail($data));
    }
}
