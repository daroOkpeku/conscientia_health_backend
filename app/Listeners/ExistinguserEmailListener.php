<?php

namespace App\Listeners;

use App\Events\ExistinguserEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendExistingEmail;
class ExistinguserEmailListener
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
    public function handle(ExistinguserEmailEvent $event): void
    {
        $data = [
            "code"=>$event->code,
            "email"=>$event->email
        ];
        Mail::to($event->email)->send( new SendExistingEmail($data) );
    }
}
