<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AppToken;
use App\Models\Doctors;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Psr7\Request as Req;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ImageKit\ImageKit;
use App\Models\Profile;

class CreateOnPatientCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:on-patient-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = AppToken::latest()->first();
        $client = new Client();

        $profiles = Profile::where(["push_to_drchrono"=>1, "onpatient_push_drchrono"=>0 ])->with([
            "primaryinsurancedata",
            "secondaryinsurancedata",
            "employeedata",
            "emergencydata",
            "responsibleparty"
        ])
            ->where(function ($query) {
                $query->whereHas('primaryinsurancedata', function ($query) {
                    $query->where('photo_front', '!=', '')
                        ->where('photo_back', '!=', '')
                        ->where('insurance_group_number', '!=', '')
                        ->where('insurance_company', '!=', '')
                        ->where('insurance_payer_id', '!=', '')
                        ->where('insurance_plan_type', '!=', '');
                })
                    ->orWhereHas('secondaryinsurancedata', function ($query) {
                        $query->where('photo_front', '!=', '')
                            ->where('photo_back', '!=', '')
                            ->where('insurance_group_number', '!=', '')
                            ->where('insurance_company', '!=', '')
                            ->where('insurance_payer_id', '!=', '')
                            ->where('insurance_plan_type', '!=', '');
                    });
            })
            ->whereHas('employeedata', function ($query) {
                $query->where('employer_name', '!=', '')
                    ->where('employer_state', '!=', '')
                    ->where('employer_city', '!=', '')
                    ->where('employer_zip_code', '!=', '')
                    ->where('employer_address', '!=', '');
            })
            ->whereHas('emergencydata', function ($query) {
                $query->where('emergency_contact_name', '!=', '')
                    ->where('emergency_contact_phone', '!=', '')
                    ->where('emergency_contact_relation', '!=', '');
            })
            ->whereHas('responsibleparty', function ($query) {
                $query->where('responsible_party_name', '!=', '')
                    ->where('responsible_party_email', '!=', '')
                    ->where('responsible_party_phone', '!=', '')
                    ->where('responsible_party_relation', '!=', '');
            })
            ->get();



            if ($profiles) {
                foreach ($profiles as $profile) {
                    $explodedoctor = explode(" ", $profile->doctor);
                    $doctors = Doctors::where("first_name", $explodedoctor[0])->first();
    
                    // Format date of birth
                    $dateString = $profile->date_of_birth;
                    $cleanDateString = substr($dateString, 0, strpos($dateString, '(')); 
    
                    $date = new \DateTime($cleanDateString);
                    $carbonDate = Carbon::instance($date);
    
                    $formattedDate = $carbonDate->format('Y-m-d');
                    $dateOfBirth = $formattedDate;
                    if (!$profile->primaryinsurancedata) {
                        return response()->json(["error" => "Primary insurance data not found for profile ID: {$profile->id}"]);
                    }
                    // Prepare multipart form data
                    $multipart = [
                        [
                            'name' => 'doctor',
                            'contents' => $doctors->drchrono_id ?? ''
                        ],
                        [
                            'name' => 'gender',
                            'contents' => $profile->gender
                        ],
                        [
                            'name' => 'first_name',
                            'contents' =>$profile->first_name  
                        ],
                        [
                            'name' => 'last_name',
                            'contents' =>$profile->last_name
                        ],
                        [
                            'name' => 'nick_name',
                            'contents' => $profile->nick_name ?? ''
                        ],
                        [
                            'name' => 'middle_name',
                            'contents' => $profile->middle_name ?? ''
                        ],
                        [
                            'name' => 'chart_id',
                            'contents' => $profile->chart_id ?? ''
                        ],
                        [
                            'name' => 'state',
                            'contents' => $profile->state ?? ''
                        ],
                        [
                            'name' => 'address',
                            'contents' => $profile->address ?? ''
                        ],
                        [
                            'name' => 'created_at',
                            'contents' => now()
                        ],
                        [
                            'name' => 'race',
                            'contents' => $profile->race ?? ''
                        ],
                        [
                            'name' => 'ethnicity',
                            'contents' => $profile->ethnicity ?? ''
                        ],
                        [
                            'name' => 'cell_phone',
                            'contents' => $profile->cell_phone ?? ''
                        ],
                        [
                            'name' => 'home_phone',
                            'contents' => $profile->home_phone ?? ''
                        ],
                        [
                            'name' => 'office_phone',
                            'contents' => $profile->office_phone ?? ''
                        ],
                        [
                            'name' => 'preferred_language',
                            'contents' => 'eng'
                        ],
                        [
                            'name' => 'patient_status',
                            'contents' => 'A'
                        ],
                        [
                            'name' => 'zip_code',
                            'contents' => $profile->zip_code ?? ''
                        ],
                        [
                            'name' => 'email',
                            'contents' => $profile->email ?? ''
                        ],
                        [
                            'name' => 'responsible_party_phone',
                            'contents' => optional($profile->responsibleparty)->responsible_party_phone ?? ''
                        ],
                        [
                            'name' => 'responsible_party_email',
                            'contents' => optional($profile->responsibleparty)->responsible_party_email ?? ''
                        ],
                        [
                            'name' => 'responsible_party_relation',
                            'contents' => optional($profile->responsibleparty)->responsible_party_relation ?? ''
                        ],
                        [
                            'name' => 'responsible_party_name',
                            'contents' => optional($profile->responsibleparty)->responsible_party_name ?? ''
                        ],
                        [
                            'name' => 'emergency_contact_phone',
                            'contents' => optional($profile->emergencydata)->emergency_contact_phone ?? ''
                        ],
                        [
                            'name' => 'emergency_contact_relation',
                            'contents' => optional($profile->emergencydata)->emergency_contact_relation ?? ''
                        ],
                        [
                            'name' => 'emergency_contact_name',
                            'contents' => optional($profile->emergencydata)->emergency_contact_name ?? ''
                        ],
    
                        [
                            'name' => 'employer_city',
                            'contents' => optional($profile->employeedata)->employer_city ?? ''
                        ],
                        [
                            'name' => 'employer_state',
                            'contents' => optional($profile->employeedata)->employer_state ?? ''
                        ],
                        [
                            'name' => 'employer',
                            'contents' => optional($profile->employeedata)->employer_name ?? ''
                        ],
                        [
                            'name' => 'employer_zip_code',
                            'contents' => optional($profile->employeedata)->employer_zip_code ?? ''
                        ],
                        [
                            'name' => 'employer_address',
                            'contents' => optional($profile->employeedata)->employer_address ?? ''
                        ],
                        [
                            'name' => 'date_of_birth',
                            'contents' => $dateOfBirth
                        ],
                        // other patient information...
                        [
                            'name' => 'primary_insurance.photo_front',
                            'contents' => fopen(optional($profile->primaryinsurancedata)->photo_front, 'r') ?? "",
                            'filename' => 'photo_front.jpg'
                        ],
                        [
                            'name' => 'primary_insurance.photo_back',
                            'contents' => fopen(optional($profile->primaryinsurancedata)->photo_back, 'r') ?? "",
                            'filename' => 'photo_back.jpg'
                        ],
                        [
                            'name' => 'secondary_insurance.photo_front',
                            'contents' => fopen(optional($profile->secondaryinsurancedata)->photo_front, 'r') ?? "",
                            'filename' => 'photo_front.jpg'
                        ],
                        [
                            'name' => 'secondary_insurance.photo_back',
                            'contents' => fopen(optional($profile->secondaryinsurancedata)->photo_back, 'r') ?? "",
                            'filename' => 'photo_back.jpg'
                        ],
                        // [
                        //     'name' => 'primary_insurance.insurance_group_name',
                        //     'contents' => optional($profile->primaryinsurancedata)->insurance_group_number ?? ""
                        // ],
                        // [
                        //     'name' => 'primary_insurance.insurance_company',
                        //     'contents' => optional($profile->primaryinsurancedata)->insurance_company ?? ""
                        // ],
                        // [
                        //     'name' => 'primary_insurance.insurance_payer_id',
                        //     'contents' => optional($profile->primaryinsurancedata)->insurance_payer_id ?? ""
                        // ],
                        // [
                        //     'name' => 'primary_insurance.insurance_plan_type',
                        //     'contents' => optional($profile->primaryinsurancedata)->insurance_plan_type ?? " "
                        // ]
    
                        [
                            'name'     => 'patient_photo',
                            'contents' => fopen($profile->patient_photo, 'r') ?? "",
                            'filename' => 'patient_photo.jpg'
                        ]
                    ];
    
    
                    // Add patient photo
    
    
                    // Send the request
                    try {
                        Log::info('Sending patient data to DrChrono API: ', $multipart);
                        $response = $client->request('POST', 'https://app.drchrono.com/api/patients/'.$profile->drchrono_patient_id.'/onpatient_access', [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $token->access_token,
                                //'Content-Type' => 'multipart/form-data', // Correct content type for file uploads
                                'Accept' => 'application/json'
                            ],
                            'multipart' => $multipart
                        ]);
                        $updatedata = json_decode($response->getBody(), true);
                        $profile->update([
                          "onpatient_push_drchrono"=>1
                        ]);
                        // Return success response
                        return response()->json(["success" => json_decode($response->getBody(), true)]);
                    } catch (\Exception $e) {
                        return response()->json(["error" => $e->getMessage()], 400);
                    }
                }
            } else {
                return response()->json(["error" => "No profiles found"], 404);
            }

    }
}
