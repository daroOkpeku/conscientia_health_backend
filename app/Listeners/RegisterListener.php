<?php

namespace App\Listeners;

use App\Events\RegisterEvent;
use App\Mail\SendRegisteremail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
class RegisterListener
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
    public function handle(RegisterEvent $event): void
    {
        $data = [
           'name'=>$event->firstname." ".$event->lastname,
           'firstname'=>$event->firstname,
           'email'=> $event->email
        ];
        Mail::to($event->email)->send(new SendRegisteremail($data));
    }
}
