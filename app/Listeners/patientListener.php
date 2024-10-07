<?php

namespace App\Listeners;

use App\Events\patientEvent;
use App\Mail\SentMailPatientCreate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class patientListener
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
    public function handle(patientEvent $event): void
    {
        $data = [
            "firstname"=>$event->first_name,
            "lastname"=>$event->last_name,
            "chatid"=>$event->chart_id
        ];
        Mail::to('info@conscientiahealth.com')->send(new SentMailPatientCreate($data));
    }
}
