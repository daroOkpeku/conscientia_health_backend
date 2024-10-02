<?php

namespace App\Jobs;

use App\Models\Responsible_party;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Responsible_party_create_request implements ShouldQueue
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
     Responsible_party::create([
        "responsible_party_name"=>$this->data["responsible_party_name"],
        "responsible_party_email"=>$this->data["responsible_party_email"],
        "responsible_party_phone"=>$this->data["responsible_party_phone"],
        "responsible_party_relation"=>$this->data["responsible_party_relation"],
        "user_id"=>$this->data["user_id"]
     ]);
    }
}
