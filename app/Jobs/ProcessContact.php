<?php

namespace App\Jobs;

use App\Events\Contactevent;
use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessContact implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $name;
    public  $email;
    public $phone;
    public  $subject;
    public  $comment;
    /**
     * Create a new job instance.
     */
    public function __construct(
        $name,
        $email,
        $phone,
        $subject,
        $comment
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->subject = $subject;
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Contact::create([
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'subject'=>$this->subject,
            'comment'=>$this->comment
        ]);
        Contactevent::dispatch(
        "info@conscientiahealth.com",
        $this->name,
        $this->email,
        $this->phone,
        $this->subject,
        $this->comment
    );
    }
}
