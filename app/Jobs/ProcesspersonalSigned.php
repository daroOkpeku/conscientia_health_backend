<?php

namespace App\Jobs;

use App\Models\Person_document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Psr7\Request as Req;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcesspersonalSigned implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    public $doctor;
    public $currentDate; 
    public $drchrono_patient_id;
    public $token;
    /**
     * Create a new job instance.
     */
    public function __construct($data, $doctor, $currentDate, $drchrono_patient_id, $token)
    {
        $this->data = $data;
        $this->doctor = $doctor;
        $this->currentDate = $currentDate;
        $this->drchrono_patient_id = $drchrono_patient_id;
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

     DB::transaction(function(){
        $person = Person_document::create([
            "name"=>$this->data["name"],
            "image"=>$this->data["image"],
            "user_id"=>$this->data["user_id"]
           ]);
    
           $client = new Client();
           $headers = [
           'Accept' => 'application/json',
           'Authorization' => 'Bearer ' . $this->token,
           'Cookie' => '_cfuvid=j9Y3D.NKlXOrxCH1TvKef1o3x3SaaGpam68qKw76yS8-1729219282125-0.0.1.1-604800000; csrftoken=ob45z5sCnc6NV2renI0uGmxSP0CB46pj1inO1VeBsnVB2oclOxFUQEryhHLHwtol'
           ];
           $options = [
           'multipart' => [
               [
               'name' => 'date',
               'contents' =>$this->currentDate
               ],
               [
               'name' => 'description',
               'contents' =>$person->name??""
               ],
               [
               'name'=>'doctor',
               'contents'=>$this->doctor??""
               ],
               [
               'name' => 'document',
               'contents'=>fopen($person->image, 'r')??"",
               //'filename' => '.pdf',
               ],
               [
               'name' => 'patient',
               'contents' =>$this->drchrono_patient_id??""
               ]
           ]];
           $request = new Req('POST', 'https://app.drchrono.com/api/documents', $headers);
           $res = $client->sendAsync($request, $options)->wait();
       
               $updatedata = json_decode($res->getBody(), true);
           Log::info('Sending patient data to DrChrono API: ', $updatedata);
    
     });

    }
}
