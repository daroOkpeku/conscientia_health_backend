<?php

namespace App\Jobs;

use App\Models\Primary_insurance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPrimary_insurance implements ShouldQueue
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
    

        Primary_insurance::create([
            "photo_front"=>$this->data["insurance_image_font"],
            "photo_back"=>$this->data["insurance_image_back"],
            "insurance_group_number"=>$this->data["insurance_group_number"],
            "insurance_company"=>$this->data["insurance_company"],
             "insurance_payer_id"=>$this->data["insurance_payer_id"],
            "insurance_plan_type"=>$this->data["insurance_plan_type"]

        ]);
        
    }
}
