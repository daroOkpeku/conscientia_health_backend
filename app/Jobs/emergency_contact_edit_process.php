<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class emergency_contact_edit_process implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
     public $data;
     public $emergency;
    /**
     * Create a new job instance.
     */
    public function __construct($data, $emergency)
    {
        $this->data = $data;
        $this->emergency = $emergency;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->emergency->update([
            "emergency_contact_name"=>$this->data['emergency_contact_name'],
            "emergency_contact_phone"=>$this->data['emergency_contact_phone'],
            "emergency_contact_relation"=>$this->data['emergency_contact_relation'],
        ]);
    }
}
