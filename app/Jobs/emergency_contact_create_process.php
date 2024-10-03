<?php

namespace App\Jobs;

use App\Models\Emergency_contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class emergency_contact_create_process implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Emergency_contact::create([
            "emergency_contact_name"=>$this->data['emergency_contact_name'],
            "emergency_contact_phone"=>$this->data['emergency_contact_phone'],
            "emergency_contact_relation"=>$this->data['emergency_contact_relation'],
            "user_id"=>$this->data['user_id']
        ]);
    }
}
