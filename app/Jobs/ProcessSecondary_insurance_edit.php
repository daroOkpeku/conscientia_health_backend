<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSecondary_insurance_edit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
     public $primary_insurance;
    /**
     * Create a new job instance.
     */
    public function __construct($data, $primary_insurance)
    {
        $this->data = $data;
        $this->primary_insurance = $primary_insurance;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->primary_insurance->update([
            "photo_front"=>$this->data["insurance_image_font"],
            "photo_back"=>$this->data["insurance_image_back"],
            "insurance_group_number"=>$this->data["insurance_group_number"],
            "insurance_company"=>$this->data["insurance_company"],
             "insurance_payer_id"=>$this->data["insurance_payer_id"],
            "insurance_plan_type"=>$this->data["insurance_plan_type"],
        ]);
    }
}
