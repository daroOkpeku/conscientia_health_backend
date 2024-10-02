<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEmployee_edit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    public $employee;
    /**
     * Create a new job instance.
     */
    public function __construct($data, $employee)
    {
        $this->data = $data;
        $this->employee = $employee;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->employee->update([
            "employer_name"=>$this->data["employer_name"],
            "employer_state"=>$this->data["employer_state"],
             "employer_city"=>$this->data["employer_city"],
             "employer_zip_code"=>$this->data["employer_zip_code"],
              "employer_address"=>$this->data["employer_address"],
        ]);
    }
}
