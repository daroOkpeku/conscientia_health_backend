<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Processprofile_edit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $proflie;
    public $data;
    /**
     * Create a new job instance.
     */
    public function __construct($proflie, $data)
    {
        $this->proflie = $proflie;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->proflie->update([
            "first_name"=>$this->data["first_name"],
            "last_name"=>$this->data["last_name"],
            "middle_name"=>$this->data["middle_name"],
            "nick_name"=>$this->data["nick_name"],
            "email"=>$this->data["email"],
            "state"=>$this->data["state"],
            "date_of_birth"=>$this->data["date_of_birth"],
            "home_phone"=>$this->data["home_phone"],
            "office_phone"=>$this->data["office_phone"],
            "cell_phone"=>$this->data["cell_phone"],
            "address"=>$this->data["address"],
            "zip_code"=>$this->data["zip_code"],
            "gender"=>$this->data["gender"],
            "race"=>$this->data["race"],
            "ethnicity"=>$this->data["ethnicity"],
            "chart_id"=>$this->data["chart_id"],
            "doctor"=>$this->data["doctor"],
            "patient_status"=>$this->data["patient_status"],
            "preferred_language"=>$this->data["preferred_language"]
        ]);
    }
}
