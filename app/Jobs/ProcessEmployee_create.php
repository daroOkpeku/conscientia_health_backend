<?php

namespace App\Jobs;

use App\Models\Employer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEmployee_create implements ShouldQueue
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
        Employer::create([
            "employer_name"=>$this->data["employer_name"],
            "employer_state"=>$this->data["employer_state"],
             "employer_city"=>$this->data["employer_city"],
             "employer_zip_code"=>$this->data["employer_zip_code"],
              "employer_address"=>$this->data["employer_address"],
              "user_id"=>$this->data["user_id"]
        ]);
    }
}
