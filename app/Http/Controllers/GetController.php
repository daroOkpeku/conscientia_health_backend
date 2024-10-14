<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Http\Resources\ProfileResourcedata;
use App\Models\AppToken;
use App\Models\Doctors;
use App\Models\Emergency_contact;
use App\Models\Employer;
use App\Models\Person_document;
use App\Models\Primary_insurance;
use App\Models\Profile;
use App\Models\Responsible_party;
use App\Models\Secondary_insurance;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Crypt;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Psr7\Request as Req;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use ImageKit\ImageKit;

class GetController extends Controller
{
    public function gencaptcha()
    {

        return response()->json(['image' => Captcha::src()]);
    }

    public function verify_email(Request $request)
    {
        $user = User::where(['email' => $request->get('email'), 'firstname' => $request->get('firstname')])->first();
        if ($user) {
            $user->update([
                'confirm_status' => 1
            ]);
            return response()->json(['success' => 'Your email has been confirmed', 'status' => true], 200);
        } else {
            return response()->json(['error' => 'Email not Found', 'status' => false], 404);
        }
    }


    public function decryptToken($token)
    {

        return  response()->json(["success" => Crypt::decrypt($token)]);
    }

    public function redirectdrchrono()
    {
        $redirectUri = env('DRCHRONO_REDIRECT_URI');
        $clientId = env('DRCHRONO_CLIENT_ID');
        // 'patients:summary:read patients:summary:write calendar:read calendar:write clinical:read clinical:write'
        $scopes = 'labs:read labs:write messages:read messages:write patients:read patients:write patients:summary:read patients:summary:write settings:read settings:write tasks:read tasks:write user:read user:write billing:patient-payment:read billing:patient-payment:write billing:read billing:write calendar:read calendar:write clinical:read clinical:write'; // Example: 'clinical:read patients:read'

        // used this api to get code to generate access_token and refresh_token
        $redirectUriEncoded = urlencode($redirectUri);
        $clientIdEncoded = urlencode($clientId);
        $scopesEncoded = urlencode($scopes);


        $authorizationUrl = "https://drchrono.com/o/authorize/?redirect_uri={$redirectUriEncoded}&response_type=code&client_id={$clientIdEncoded}&scope={$scopesEncoded}";


        try {

            return response()->json([
                'body' => $authorizationUrl
            ], 200);
        } catch (\Exception $e) {
            // Handle errors and exceptions
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //https://e2da-102-219-153-211.ngrok-free.app/api/auth/callback/drchrono?code=HeucgrQqBA4lPz3hKyYt3UycSPkb4O

    public function drchrono(Request $request)
    {
        // Define the parameters required for the authorization URL
        $code = $request->get('code');
        $checktoken = AppToken::all();

        if (count($checktoken) == 0) {
            $apptoken = AppToken::create([
                "code" => $code
            ]);
            return response()->json(['success' => "successfull"], 200);
        } else {
            $checktokenexist = optional(AppToken::whereNotNull("code")->where("code", "!=", ""))->first();
            $checktokenexist->update([
                "code" => $code
            ]);
            return response()->json(['success' => "successfull"], 200);
        }
     }




    public function getAccessToken(Request $request)
    {

        if (isset($getParams['error'])) {
            throw new Exception('Error authorizing application: ' . $getParams['error']);
        }
        $checktoken = AppToken::latest()->first();
        $redirectUri = env('DRCHRONO_REDIRECT_URI');
        $clientId = env('DRCHRONO_CLIENT_ID');
        $sercet = env("DRCHRONO_CLIENT_SECRET");
        // Prepare the data for the POST request
        $postData = [
            'code' => $checktoken->code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'client_secret' => $sercet,
        ];
        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://drchrono.com/o/token/');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            throw new Exception('Request Error: ' . curl_error($ch));
        }

        // Get the HTTP status code of the response
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close cURL resource
        curl_close($ch);

        // Check for non-200 status code
        if ($httpCode !== 200) {
            throw new Exception('Failed to retrieve access token. HTTP Status Code: ' . $httpCode);
        }

        // Decode the JSON response
        $data = json_decode($response, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON decoding error: ' . json_last_error_msg());
        }

        // Save these in your database associated with the user
        $accessToken = $data['access_token'];
        $refreshToken = $data['refresh_token'];
        $expiresTimestamp = (new DateTime('now', new DateTimeZone('UTC')))->modify('+' . $data['expires_in'] . ' seconds');
        if ($checktoken) {
            $checktoken->update([
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_timestamp' => $expiresTimestamp->format('Y-m-d H:i:s') . " UTC",
            ]);

            // Output or save the tokens and expiry information as needed
            return response()->json(["success" => 'successful']);
        }
    }


    public function showtest(Request $request) {}

    public function list_doctors(Request $request)
    {
        // Chika, Lauracel, Laura and Amaka
        $doctors = Doctors::whereNotNull('job_title')->whereIn('first_name', ['Amaka', 'Chika',  'Laracel', 'Laura'])
            ->where(['is_account_suspended' => 0, 'is_new_patient' => 1])
            //   ->inRandomOrder()
            //   ->take(3)
            ->get();
        $checktoken = AppToken::whereNotNull("code")->where("code", "!=", "")->first();

        if ($checktoken) {

            $date = Carbon::now()->format('Y-m-d');
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$checktoken->access_token}",
            ];
            $kod = array();
            foreach ($doctors as  $doctor) {

                $request = new Req('GET', "https://app.drchrono.com/api/appointments?date={$date}&doctor={$doctor->drchrono_id}", $headers);
                $res = $client->sendAsync($request)->wait();
                $body = $res->getBody()->getContents();
                $data = json_decode($body, true);


                for ($i = 0; $i < count($data); $i++) {
                    # code...
                    $date = Carbon::parse($data['results'][$i]["scheduled_time"] ?? "");
                    $formattedDate = $date->format('m-d-y H:i:s') ?? "";
                    $jojo = [
                        "doctor" => $doctor->first_name ?? "" . " " . $doctor->last_name ?? "",
                        "specialty" => $doctor->specialty ?? "",
                        "picture" => $doctor->profile_picture ?? "",
                        "day" => $data['results'][$i]['recurring_days'] ?? '',
                        "time" => $formattedDate ?? '',
                        "age_start" => $doctor->age_start ?? '',
                        "age_end" => $doctor->age_end ?? '',
                        "suffix" => $doctor->suffix ?? ''
                    ];
                    array_push($kod, $jojo);
                }
                //  $jojo = [
                //     "doctor"=>$doctor->first_name." ".$doctor->last_name,
                //     "specialty"=>$doctor->specialty,
                //     "picture"=>$doctor->profile_picture,
                //     "day"=>$data['results'][0]['recurring_days'],
                //     "time"=>$formattedDate
                //  ];


            }

            $cacheKey = 'token' . $checktoken->access_token;
            $data = Cache::remember($cacheKey, 3600, function () use ($kod) {
                return $kod;
            });

            return response()->json(['success' => $data]);
        }
    }


    public function doctor_availiable()
    {
        //     $doctors = Doctors::whereNotNull('job_title')
        //     ->where('is_account_suspended', 0)->where('first_name', '!=', 'Simbiat')
        //     ->inRandomOrder()
        //     ->take(3)
        //     ->get();

        // $client = new Client();
        // $headers = [
        //   'Content-Type' => 'application/json',
        //   'Accept' => 'application/json',
        //   'Authorization' => 'Bearer RCuBUatfnxaMsDpy4X6oSSWjH5NwwU',
        // ];
        // $status='available';
        // $request = new Req('GET', "https://app.drchrono.com/api/appointments?doctor=322461&status={$status}", $headers);
        // $res = $client->sendAsync($request)->wait();
        // $body = $res->getBody()->getContents();
        // $data = json_decode($body, true);
        // return response()->json($data);
    }

    public function state_age_check(Request $request)
    {
        // $state = 'NJ';
        $doctors = Doctors::whereNotNull('job_title')
            ->where(['is_account_suspended' => 0, 'is_new_patient' => 1])
            ->where('age_start', '<=', $request->get('age'))
            ->where('age_end', '>=', $request->get('age'))
            ->whereJsonContains('states', $request->get('state'))
            ->get();

        if ($doctors) {
            $cacheKey = 'doctor_' . $request->get('age') . '_' . $request->get('state');
            $data = Cache::remember($cacheKey, 3600, function () use ($doctors) {
                return $doctors;
            });
            return response()->json(["success" => $data], 200);
        } else {
            return response()->json(['error' => "We don't have any providers in the state you selected. Please call us at +877-803-5342 or email us at info@conscientiahealth.com."], 200);
        }
    }


    public function get_profile($editid)
    {
        if ($editid) {
            $profile = Profile::with('userData')->where("user_id", $editid)->first();
            if ($profile) {
                $data = ProfileResource::make($profile)->resolve();
                // $data = $profile->userdata;
                $cacheKey = 'profile_' . $profile->user_id . '_' . $profile->email;
                $datax = Cache::remember($cacheKey, 3600, function () use ($data) {
                    return $data;
                });
                return response()->json(["success" => $datax], 200);
            }
        } else {
            return response()->json(["error" => "does not exist"]);
        }
    }

    public function primary_get($user_id)
    {
        $primary = Primary_insurance::where("user_id", $user_id)->first();
        if ($primary) {
            $cacheKey = 'primary_' . $primary->user_id;
            $datax = Cache::remember($cacheKey, 3600, function () use ($primary) {
                return $primary;
            });
            return response()->json(["success" => $datax]);
        }
    }

    public function secondary_get($user_id)
    {
        $primary = Secondary_insurance::where("user_id", $user_id)->first();
        if ($primary) {
            $cacheKey = 'secondary_' . $primary->user_id;
            $datax = Cache::remember($cacheKey, 3600, function () use ($primary) {
                return $primary;
            });
            return response()->json(["success" => $datax]);
        }
    }

    public function employer_get($user_id)
    {
        $employer = Employer::where("user_id", $user_id)->first();
        if ($employer) {
            $cacheKey = 'employer_' . $employer->user_id;
            $datax = Cache::remember($cacheKey, 3600, function () use ($employer) {
                return $employer;
            });
            return response()->json(["success" => $datax]);
        } else {
            return response()->json(["error" => "something went wrong"]);
        }
    }


    public function responsible_party_get($user_id)
    {
        $responsible = Responsible_party::where("user_id", $user_id)->first();
        if ($responsible) {
            $cacheKey = 'responsible_' . $responsible->user_id;
            $datax = Cache::remember($cacheKey, 3600, function () use ($responsible) {
                return $responsible;
            });
            return response()->json(["success" => $datax]);
        } else {
            return response()->json(["error" => "something went wrong"]);
        }
    }


    public function emergency_get($user_id)
    {
        $responsible = Emergency_contact::where("user_id", $user_id)->first();
        if ($responsible) {
            $cacheKey = 'emergency_' . $responsible->user_id;
            $datax = Cache::remember($cacheKey, 3600, function () use ($responsible) {
                return $responsible;
            });
            return response()->json(["success" => $datax]);
        } else {
            return response()->json(["error" => "something went wrong"]);
        }
    }



    public function uploadPicture(Request $request)
    {
        $imageKit = new ImageKit(
            "public_ubzgMmE6xfs3M3PhH7b0RdPCNVs=",
            "private_i8k1ateHiH63X3zO4lxSNn6bLWk=",
            "https://ik.imagekit.io/mhtpe5cvo"
        );

        // Convert image to base64 and upload
        $fileContent = base64_encode(file_get_contents($request->file('image')->getRealPath()));  // Make sure image is in base64 format
        $uploadFile = $imageKit->uploadFile([
            'file' => $fileContent,
            'fileName' => 'new-file'   // Unique file name
        ]);

        return response()->json(["success" => $uploadFile->result->url]);
    }


    public function uploadPictureBase(Request $request)
{
    // Check if the base64 image is present in the request
    if ($request->has('image')) {
        $imageKit = new ImageKit(
            "public_ubzgMmE6xfs3M3PhH7b0RdPCNVs=",
            "private_i8k1ateHiH63X3zO4lxSNn6bLWk=",
            "https://ik.imagekit.io/mhtpe5cvo"
        );

        // The file is already base64-encoded on the frontend
        $fileContent = $request->input('image');

        // Upload to ImageKit
        $uploadFile = $imageKit->uploadFile([
            'file' => $fileContent,
            'fileName' => 'new-file.pdf',  // Set appropriate file extension
            'fileType' => 'application/pdf'
        ]);

        return response()->json(["success" => $uploadFile->result->url]);
    } else {
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}



    // public function uploadPatientCreate(){
    //      $token =  AppToken::latest()->first();
    //     $client = new Client();

    // $profiles = Profile::with(["primaryinsurancedata", "secondaryinsurancedata", "employeedata", "emergencydata", "responsibleparty"])->get();
    // //  return response()->json(["success"=>$profiles[0]['primaryinsurancedata']]);
    // $headers = [
    //     'Authorization' => 'Bearer '.$token->access_token,  // Replace with your actual token
    //     'Content-Type'        => 'application/json',
    // ];


    // if($profiles){
    //    foreach ($profiles as  $profile) {
    //     $profile->doctor;
    //     $explodedoctor = explode(" ",  $profile->doctor);
    //    $doctors = optional(Doctors::where("first_name", $explodedoctor[0]))->first();

    //    $dateOfBirth = Carbon::createFromTimestamp(strtotime($profile->date_of_birth))->format('Y-m-d');
    //      $options = [
    //         [
    //             'name'     => 'doctor',
    //             'contents' => $doctors->drchrono_id
    //         ],
    //         [
    //             'name'     => 'gender',
    //             'contents' => $profile->gender
    //         ],
    //         // [
    //         //     'name'     => 'first_name',
    //         //     'contents' => $profile->first_name
    //         // ],
    //         // [
    //         //     'name'     => 'last_name',
    //         //     'contents' => $profile->last_name
    //         // ],
    //         [
    //             'name'     => 'first_name',
    //             'contents' => "Primegen"
    //         ],
    //         [
    //             'name'     => 'last_name',
    //             'contents' => "time"
    //         ],
    //         [
    //             'name'     => 'nick_name',
    //             'contents' => $profile->nick_name
    //         ],

    //         [
    //             'name'     => 'middle_name',
    //             'contents' => $profile->middle_name
    //         ],

    //         [
    //             'name'     => 'state',
    //             'contents' => $profile->state
    //         ],

    //         [
    //             'name'     => 'address',
    //             'contents' => $profile->address
    //         ],
    //         [
    //             'name'     => 'created_at',
    //             'contents' => now()
    //         ],
    //         [
    //             'name'     => 'race',
    //             'contents' =>$profile->race
    //         ],
    //         [
    //             'name'     => 'ethnicity',
    //             'contents' =>$profile->ethnicity
    //         ],
    //         // [
    //         //     'name'     => 'chart_id',
    //         //     'contents' => $profile->chart_id
    //         // ],
    //         [
    //             'name'     => 'cell_phone',
    //             'contents' => $profile->cell_phone
    //         ],
    //         [
    //             'name'     => 'home_phone',
    //             'contents' => $profile->home_phone
    //         ],

    //         [
    //             'name'     => 'office_phone',
    //             'contents' =>  $profile->office_phone
    //         ],
    //         [
    //             'name'     => 'preferred_language',
    //             'contents' => 'eng'
    //         ],
    //         [
    //             'name'     => 'patient_status',
    //             'contents' => 'A'
    //         ],
    //         [
    //             'name'     => 'zip_code',
    //             'contents' =>  $profile->zip_code
    //         ],
    //         [
    //             'name'     => 'email',
    //             'contents' => $profile->email
    //         ],

    //         [
    //             'name'     => 'responsible_party_phone',
    //             'contents' =>optional($profile->responsibleparty)->responsible_party_phone ?? ""
    //         ],

    //         [
    //             'name'     => 'responsible_party_email',
    //             'contents' =>optional($profile->responsibleparty)->responsible_party_email ?? ""
    //         ],
    //         [
    //             'name'     => 'responsible_party_relation',
    //             'contents' =>optional($profile->responsibleparty)->responsible_party_relation ?? ""
    //         ],
    //         [
    //             'name'     => 'responsible_party_name',
    //             'contents' =>optional($profile->responsibleparty)->responsible_party_name ?? ""
    //         ],

    //         [
    //             'name'     => 'emergency_contact_phone',
    //             'contents' =>optional($profile->emergencydata)->emergency_contact_phone ?? ""
    //         ],

    //         [
    //             'name'     => 'emergency_contact_relation',
    //             'contents' =>optional($profile->emergencydata)->emergency_contact_relation ?? ""
    //         ],

    //         [
    //             'name'     => 'emergency_contact_name',
    //             'contents' =>optional($profile->emergencydata)->emergency_contact_name ?? ""
    //         ],

    //         [
    //             'name'     => 'date_of_birth',
    //             'contents' => $dateOfBirth
    //         ],
    //         // patient_payment_profile
    //         // [
    //         //     'name'     => 'patient_payment_profile',
    //         //     'contents' => "Insurance"
    //         // ],

    //         [
    //             'name'     => 'primary_insurance[photo_front]',
    //             'contents' => fopen(optional($profile->primaryinsurancedata)->photo_front, 'r') ?? "",
    //             'filename' => 'photo_front.jpg'
    //         ],
    //         [
    //             'name'     => 'primary_insurance[photo_back]',
    //             'contents' => fopen(optional($profile->primaryinsurancedata)->photo_back, 'r') ?? "",
    //             'filename' => 'photo_back.jpg'
    //         ],

    //         [
    //             'name'     => 'primary_insurance[insurance_group_name]',
    //             'contents' =>optional($profile->primaryinsurancedata)->insurance_group_number??"",
    //         ],

    //         [
    //             'name'     => 'primary_insurance[insurance_company]',
    //             'contents' =>optional($profile->primaryinsurancedata)->insurance_company??"",
    //         ],

    //         [
    //             'name'     => 'primary_insurance[insurance_payer_id]',
    //             'contents' =>optional($profile->primaryinsurancedata)->insurance_payer_id??"",
    //         ],

    //         [
    //             'name'     => 'primary_insurance[insurance_plan_type]',
    //             'contents' =>optional($profile->primaryinsurancedata)->insurance_plan_type??" ",
    //         ],
    //         // [
    //         //     'name'     =>'primary_insurance[is_subscriber_the_patient]',
    //         //     'contents' =>true,
    //         // ],

    //         [
    //             'name'     => 'patient_photo',
    //             'contents' => fopen($profile->patient_photo, 'r'),  // Replace with actual image URL
    //             'filename' => 'photo_back.jpg'
    //         ]

    // ];
    //     $request = new Req('POST', 'https://app.drchrono.com/api/patients', $headers);
    //     $res = $client->send($request, ['multipart' =>$options]);
    //     return response()->json(["success"=>$res->getBody()]);


    //    }


    // }else{
    //     return response()->json(["error"=>"something went wrong"]);
    // }

    // }



    public function uploadPatientCreate()
    {
        $token = AppToken::latest()->first();
        $client = new Client();

        $profiles = Profile::where("push_to_drchrono", 0)->with([
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

        // Check if profiles exist
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
                        'contents' => "Zeter testing"  // Hardcoded values, you can replace with actual data
                    ],
                    [
                        'name' => 'last_name',
                        'contents' => "james"
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
                    $response = $client->request('POST', 'https://app.drchrono.com/api/patients', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $token->access_token,
                            //'Content-Type' => 'multipart/form-data', // Correct content type for file uploads
                            'Accept' => 'application/json'
                        ],
                        'multipart' => $multipart
                    ]);
                    $updatedata = json_decode($response->getBody(), true);
                    $profile->update([
                        "chart_id" => $updatedata["chart_id"],
                        "drchrono_patient_id" => $updatedata["id"],
                        "push_to_drchrono" => 1
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





    public function on_patient_upload()
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
                            'contents' => "Zeter testing"  // Hardcoded values, you can replace with actual data
                        ],
                        [
                            'name' => 'last_name',
                            'contents' => "james"
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




    public function recentuploaddoc(Request $request){
        $person_document = Person_document::where([
            'name' => $request->get("name"),
            'user_id' => $request->get("user_id")
        ])->orderBy('created_at', 'desc')->first();
     if($person_document){
      return response()->json(["name"=>$request->get("name"), "status"=>true, 'link'=>$person_document->image],200);
    }else{
        return response()->json(["name"=>$request->get("name"), "status"=>false, 'link'=>""],200);
    }
    }










}
