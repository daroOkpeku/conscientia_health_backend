<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Responsible_party_edit_process implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
     public $data;
     public $response;
    /**
     * Create a new job instance.
     */
    public function __construct($data, $response)
    {
       $this->data = $data;
       $this->response = $response;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->response->update([
            "responsible_party_name"=>$this->data['responsible_party_name'],
            "responsible_party_email"=>$this->data['responsible_party_email'],
            "responsible_party_phone"=>$this->data['responsible_party_phone'],
            "responsible_party_relation"=>$this->data['responsible_party_relation'],
        ]);
    }
}
