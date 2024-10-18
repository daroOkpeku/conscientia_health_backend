<?php

namespace App\Http\Repository;

use App\Http\Repository\Contracts\PostRespositoryinterface;
use App\Jobs\emergency_contact_create_process;
use App\Jobs\emergency_contact_edit_process;
use App\Jobs\ProcessContact;
use App\Jobs\ProcessEmployee_create;
use App\Jobs\ProcessEmployee_edit;
use App\Jobs\ProcesspersonalSigned;
use App\Jobs\ProcessPrimary_insurance;
use App\Jobs\ProcessPrimary_insurance_edit;
use App\Jobs\Processprofile_create;
use App\Jobs\Processprofile_edit;
use App\Jobs\ProcessSecondary_insurance;
use App\Jobs\ProcessSecondary_insurance_edit;
use App\Jobs\Responsible_party_create_request;
use App\Jobs\Responsible_party_edit_process;
use App\Models\AppToken;
use App\Models\Booking;
use App\Models\Doctors;
use App\Models\Emergency_contact;
use App\Models\Employer;
use App\Models\Primary_insurance;
use App\Models\Profile;
use App\Models\Responsible_party;
use App\Models\Secondary_insurance;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use ImageKit\ImageKit;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Psr7\Request as Req;
class PostRepository implements PostRespositoryinterface
{

public function contact($request){
ProcessContact::dispatchSync(
   $request->name,
   $request->email,
   $request->phone,
   $request->subject,
   $request->comment
);
return response()->json(['success'=>"we will get back to you soon"], 200);
}

public function uploadImage($image)
{
    $imageKit = new ImageKit(
        "public_ubzgMmE6xfs3M3PhH7b0RdPCNVs=",
        "private_i8k1ateHiH63X3zO4lxSNn6bLWk=",
        "https://ik.imagekit.io/mhtpe5cvo"
    );

    // Convert image to base64 and upload
    $fileContent = base64_encode($image);  // Make sure image is in base64 format
    $uploadFile = $imageKit->uploadFile([
        'file' => $fileContent,
        'fileName' => 'new-file'   // Unique file name
    ]);

    return $uploadFile;
}


public function uploadImagexd($image)
{
    $imageKit = new ImageKit(
        "public_ubzgMmE6xfs3M3PhH7b0RdPCNVs=",
        "private_i8k1ateHiH63X3zO4lxSNn6bLWk=",
        "https://ik.imagekit.io/mhtpe5cvo"
    );

    // Convert image to base64 and upload
    $fileContent = base64_encode($image);  // Make sure image is in base64 format
    $uploadFile = $imageKit->uploadFile([
        'file' => $fileContent,
        'fileName' => 'new-file'   // Unique file name
    ]);

    return $uploadFile;
}
public function generateotpcode($first_name, $last_name){
    $randnum = rand(00000, 99999);
    $time = now();


    $randnum_first_three = substr($randnum, 0, 3);
    $time_seconds = $time->format('s');
    $time_first_three = substr($time_seconds, 0, 3);
   $first_text = strtoupper(substr($first_name, 0, 2));
   $last_text =  strtoupper(substr($last_name, 0, 2));

    $joined_value = $last_text.$first_text.$randnum_first_three . $time_first_three;
    return $joined_value;
}

public function bookingData($email){
    $booking = Booking::where("email", $email)->orderBy('created_at', 'desc')->first();
    if($booking){
      $explodedoctor = explode(" ", $booking->doctor);
      $explodedoctor[1];
     $doctors = optional(Doctors::where("last_name", $explodedoctor[1]))->first();

    return $doctors->first_name." ".$doctors->last_name;
    }else{
        return "nothing";
    }

}

public function profile_create($request){
  $chat_id =  $this->generateotpcode($request->first_name, $request->last_name);
  $doctor =  $request->doctor?$request->doctor:$this->bookingData($request->email);

$data = [
    "first_name"=>$request->first_name,
    "last_name"=>$request->last_name,
    "middle_name"=>$request->middle_name,
    "nick_name"=>$request->nick_name,
    "email"=>$request->email,
    "state"=>$request->state,
    "date_of_birth"=>$request->date_of_birth,
    "home_phone"=>$request->home_phone,
    "office_phone"=>$request->office_phone,
    "cell_phone"=>$request->cell_phone,
    "address"=>$request->address,
    "zip_code"=>$request->zip_code,
    "gender"=>$request->gender,
    "chart_id"=>$chat_id,
    "doctor"=>$doctor,
    "patient_status"=>"A",
    "preferred_language"=>"en",
    "race"=>$request->race,
    "ethnicity"=>$request->ethnicity,
    "user_id"=>$request->user_id
];
Processprofile_create::dispatchSync($data);
return response()->json(["success"=>"You have Successfully Created your Profile"],200);
}



public function profile_edit($request){
$proflie = Profile::find($request->id);
if($proflie){
  $doctor = $request->doctor?$request->doctor:$this->bookingData($request->email);
  $chat_id =  $this->generateotpcode($request->first_name, $request->last_name);

    $data = [
        "first_name"=>$request->first_name,
        "last_name"=>$request->last_name,
        "middle_name"=>$request->middle_name,
        "nick_name"=>$request->nick_name,
        "email"=>$request->email,
        "state"=>$request->state,
        "date_of_birth"=>$request->date_of_birth,
        "home_phone"=>$request->home_phone,
        "office_phone"=>$request->office_phone,
        "cell_phone"=>$request->cell_phone,
        "address"=>$request->address,
        "zip_code"=>$request->zip_code,
        "gender"=>$request->gender,
        "chart_id"=>$chat_id,
        "doctor"=>$doctor,
        "patient_status"=>"A",
        "preferred_language"=>"en",
        "race"=>$request->race,
        "ethnicity"=>$request->ethnicity,
    ];
Processprofile_edit::dispatchSync($proflie, $data);
return response()->json(["success"=>"You have Successfully update your Profile"],200);
}else{
    return response()->json(["error"=>"something went wrong"],200);
}
}


private function processImageOrString($input, $requestField) {
    if (is_string($input)) {
        // If the input is a string, return the string directly
        return $input;
    } elseif ($requestField->hasFile($input) && $requestField->file($input)->isValid()) {
        // If the input is a valid file, upload the image
        return $this->uploadImagexd(file_get_contents($requestField->file($input)->getRealPath()))->result->url;
    }
    // Handle cases where neither string nor valid file is provided
    throw new \Exception("Invalid input for $input. Expected a string or a valid image.");
}

public function primary_insurance_create($request)
{

    $data = [
        "insurance_group_number"=>$request->insurance_group_number,
        "insurance_company"=>$request->insurance_company,
        "insurance_payer_id"=>$request->insurance_payer_id,
        "insurance_plan_type"=>$request->insurance_plan_type,
        "insurance_image_font"=>$request->insurance_image_font,
        "insurance_image_back"=>$request->insurance_image_back,
        "user_id"=>$request->user_id
    ];

    ProcessPrimary_insurance::dispatch($data);
    return response()->json(["success"=>"your Primary Insurance has been Created"]);
}

public function primary_insurance_edit($request){

        // $insurance_image_font = file_get_contents($request->file('insurance_image_font')->getRealPath());

    $data = [
        "insurance_group_number"=>$request->insurance_group_number,
        "insurance_company"=>$request->insurance_company,
        "insurance_payer_id"=>$request->insurance_payer_id,
        "insurance_plan_type"=>$request->insurance_plan_type,
        "insurance_image_font"=>$request->insurance_image_font,
        "insurance_image_back"=>$request->insurance_image_back,
            "user_id"=>$request->user_id
    ];
  $primary_insurance =  Primary_insurance::where("user_id", $request->user_id)->first();
  if($primary_insurance){
    ProcessPrimary_insurance_edit::dispatchSync($data, $primary_insurance);
    return response()->json(["success"=>"your Primary Insurance has been Update"]);
  }else{
    return response()->json(["error"=>"you record does not exist"]);
  }
}




public function uploadprofileimage($request)
{
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $path = file_get_contents($file->getRealPath());  // This works for a single file
        $profile = Profile::where("user_id", $request->user_id)->first();

        $imglink = $this->uploadImage($path);

        if ($profile) {
            // Update existing profile with new image URL
            $profile->update([
                "patient_photo" =>$imglink->result->url
            ]);
            return response()->json(["success" => "Your image has been updated"], 200);
        } else {
            // Create a new profile with the uploaded image URL
            Profile::create([
                "patient_photo" =>$imglink->result->url,
                "user_id" => $request->user_id
            ]);
            return response()->json(["success" => "Your image has been uploaded"], 200);
        }
    }else{
        return response()->json(['error'=>"something went wrong"],200);
    }
}

public function change_password($request){
  $user =  User::where("email", $request->email)->first();
  if($user){
    if(!Hash::check($request->old_password, $user->password)){
        return response()->json(["error"=>"Please insert the correct password"],200);
    }

    $user->update([
        "password"=>Hash::make($request->password)
    ]);
     return response()->json(["success"=>"you have update your password"],200);
  }else{
    return response()->json(["error"=>"something went errror"]);
  }

}



public function secondary_insurance_create($request)
{

    $data = [
        "insurance_group_number"=>$request->insurance_group_number,
        "insurance_company"=>$request->insurance_company,
        "insurance_payer_id"=>$request->insurance_payer_id,
        "insurance_plan_type"=>$request->insurance_plan_type,
        "insurance_image_font"=>$request->insurance_image_font,
        "insurance_image_back"=>$request->insurance_image_back,
            "user_id"=>$request->user_id
    ];
     ProcessSecondary_insurance::dispatch($data);
    return response()->json(["success"=>"your Secondary Insurance has been Created"]);
}



public function secondary_insurance_edit($request){


$data = [
    "insurance_group_number"=>$request->insurance_group_number,
    "insurance_company"=>$request->insurance_company,
    "insurance_payer_id"=>$request->insurance_payer_id,
    "insurance_plan_type"=>$request->insurance_plan_type,
    "insurance_image_font"=>$request->insurance_image_font,
    "insurance_image_back"=>$request->insurance_image_back,
    "user_id"=>$request->user_id
];
$primary_insurance =  Secondary_insurance::where("user_id", $request->user_id)->first();
if($primary_insurance){
ProcessSecondary_insurance_edit::dispatchSync($data, $primary_insurance);
return response()->json(["success"=>"your Primary Insurance has been Update"]);
}else{
return response()->json(["error"=>"you record does not exist"]);
}
}


public function employee_create($request){
    $data = [
        "employer_name"=>$request->employer_name,
        "employer_state"=>$request->employer_state,
         "employer_city"=>$request->employer_city,
         "employer_zip_code"=>$request->employer_zip_code,
          "employer_address"=>$request->employer_address,
          "user_id"=>$request->user_id
    ];
   ProcessEmployee_create::dispatchSync($data);
   return response()->json(["success"=>"you have inserted your Employer details"]);
}

public function employee_edit($request){
    $data = [
        "employer_name"=>$request->employer_name,
        "employer_state"=>$request->employer_state,
         "employer_city"=>$request->employer_city,
         "employer_zip_code"=>$request->employer_zip_code,
          "employer_address"=>$request->employer_address,
          "user_id"=>$request->user_id
    ];
    $employee = Employer::where(["user_id"=>$request->user_id])->first();

    if($employee){
       ProcessEmployee_edit::dispatchSync($data, $employee);
       return response()->json(["success"=>"you have updated your Employee details"]);
    }else{
        return response()->json(["error"=>"something went wrong"]);
    }
}

public function responsible_party_create($request){
    $data = [
        "responsible_party_name"=>$request->responsible_party_name,
        "responsible_party_email"=>$request->responsible_party_email,
        "responsible_party_phone"=>$request->responsible_party_phone,
        "responsible_party_relation"=>$request->responsible_party_relation,
        "user_id"=>$request->user_id
    ];
    Responsible_party_create_request::dispatchSync($data);
    return response()->json(["success"=>"you have inserted your responsible party details"]);
}


public function responsible_party_edit($request){
    $data = [
        "responsible_party_name"=>$request->responsible_party_name,
        "responsible_party_email"=>$request->responsible_party_email,
        "responsible_party_phone"=>$request->responsible_party_phone,
        "responsible_party_relation"=>$request->responsible_party_relation,
        "user_id"=>$request->user_id
    ];
    $response = Responsible_party::where("user_id", $request->user_id)->first();
    if($response){
        Responsible_party_edit_process::dispatchSync($data, $response);
        return response()->json(["success"=>"you have edited your responsible party details"]);
    }else{
        return response()->json(["error"=>"something went wrong"]);
    }
}

public function emergency_contact_create($request){
    $data =  [
        "emergency_contact_name"=>$request->emergency_contact_name,
        "emergency_contact_phone"=>$request->emergency_contact_phone,
        "emergency_contact_relation"=>$request->emergency_contact_relation,
        "user_id"=>$request->user_id
    ];

    emergency_contact_create_process::dispatchSync($data);
    return response()->json(["success"=>"you have edited your emergency contact details"]);
}

public function emergency_contact_edit($request){
    $data =  [
        "emergency_contact_name"=>$request->emergency_contact_name,
        "emergency_contact_phone"=>$request->emergency_contact_phone,
        "emergency_contact_relation"=>$request->emergency_contact_relation,
        "user_id"=>$request->user_id
    ];
    $emergency = Emergency_contact::where("user_id", $request->user_id)->first();
    if($emergency){
        emergency_contact_edit_process::dispatch($data, $emergency);
        return response()->json(["success"=>"you have edited your emergency contact details"]);
    }else{
        return response()->json(["error"=>"something went wrong"]);
    }

}

public function personal_signed($request){
    $data =[
        "image"=>$request->image,
        "name"=>$request->name,
        "user_id"=>$request->user_id
    ];
    $token = AppToken::latest()->first();
    $profile = Profile::where("user_id", $request->user_id)->first();
    if($profile){
        $doctorNames = explode(" ", $profile->doctor);
        $doctor = Doctors::where("first_name", $doctorNames[0])->first();
        if (!$doctor || empty($doctor->drchrono_id)) {
            return response()->json(["error" => "Doctor ID is missing or doctor not found"], 400);
        }
        $currentDate = date('Y-m-d');
        ProcesspersonalSigned::dispatchSync($data, $doctor->drchrono_id, $currentDate, $profile->drchrono_patient_id, $token->access_token);
        return response()->json(["success"=>"you have succesfully uploaded the document"]);
    }

}


// public function consent_upload($request){
//     $data = json_decode($request->data, true);
//     // echo $data;
//      $user_id =  $request->user_id;
//     $profile = Profile::where("user_id", $user_id)->first();
//     if(!$profile){
//         return response()->json(["error"=>"your profile does not exist"]);
//     }
//     $profile->drchrono_patient_id;
//     $explodedoctor = explode(" ", $profile->doctor);
//     $doctors = optional(Doctors::where("first_name", $explodedoctor[0])->first());
//     $finish = "";
//     $currentDate = date('Y-m-d');
//     if(count($data) > 0){
//         $token = AppToken::latest()->first();
//         $lastElement = end($data);

//         foreach ($data as $byte) {
//             if($byte["name"] == $lastElement["name"] && $byte["name"] == "Professional references"){
//                $finish .="all your document has be uploded";
//                $client = new Client();

//                if (empty($byte["name"])) {
//                 return response()->json(["error" => "Description (byte[name]) is missing"], 400);
//             }

//             if (empty($doctors->drchrono_id)) {
//                 return response()->json(["error" => "Doctor ID is missing"], 400);
//             }



//             if (empty($profile->drchrono_patient_id)) {
//                 return response()->json(["error" => "Patient ID is missing"], 400);
//             }


//                 $documentPath = $byte["link"];
//                 $fixedUrl = str_replace("//", "/", $documentPath);

//                 // Ensure the "https://" is restored properly:
//                 $fixedUrl = preg_replace("#^https:/#", "https://", $fixedUrl);

//                 // Use file_get_contents or fopen for remote URLs:
//                 $documentContent = fopen($fixedUrl, 'r');

//                 if ($documentContent !== false) {
//                     // Process the document content here...
//                 } else {
//                     return response()->json(["error" => "File not found: " . $fixedUrl], 400);
//                 }




//               $options = [
//                [
//                  'name' => 'date',
//                  'contents' =>$currentDate
//                ],
//                [
//                  'name' => 'description',
//                  'contents' =>$byte["name"]??""
//                ],
//                [
//                  'name' => 'doctor',
//                  'contents' =>$doctors->drchrono_id??""
//                ],
//                [
//                    'name' => 'document',
//                    'contents' => $documentContent,
//                    'filename' =>'image.jpg'
//                ],
//                [
//                  'name' => 'patient',
//                  'contents' =>$profile->drchrono_patient_id??""
//                ],
//             //    [
//             //      'name' => 'metatags',
//             //      'contents' => ''
//             //    ]
//            ];
//             //    $res = $client->request('POST', 'https://app.drchrono.com/api/documents', [
//             //        'headers' => $headers,
//             //        'multipart' => $options
//             //    ]);

//                $res = $client->request('POST', 'https://app.drchrono.com/api/documents', [
//                 'headers' => [
//                     'Authorization' => 'Bearer '.$token->access_token,
//                     'Content-Type' => 'multipart/form-data', // Correct content type for file uploads
//                    // 'Accept' => 'application/json'
//                 ],
//                 'multipart' => $options
//             ]);

//                $updatedata = json_decode($res->getBody(), true);
//                Log::info('Sending patient data to DrChrono API: ', $updatedata);


//             }
//         }

//        return response()->json(["success"=>$lastElement],200);
//     }else{
//         return response()->json(["error"=>"something went wrong"]);
//     }



// }


public function consent_upload($request)
{
    $data = json_decode($request->data, true);
    $user_id = $request->user_id;
    $profile = Profile::where("user_id", $user_id)->first();

    if (!$profile) {
        return response()->json(["error" => "Your profile does not exist"], 404);
    }

    $doctorNames = explode(" ", $profile->doctor);
    $doctor = Doctors::where("first_name", $doctorNames[0])->first();
    $currentDate = date('Y-m-d');

    if (count($data) > 0) {
        $token = AppToken::latest()->first();
        $lastElement = end($data);
        $finish = "All your documents have been uploaded";

        //$client = new Client();

        foreach ($data as $byte) {
            if ($byte["name"] === $lastElement["name"]) {
                // Validate required fields
                if (empty($byte["name"])) {
                    return response()->json(["error" => "Description (byte[name]) is missing"], 400);
                }

                if (empty($doctor->drchrono_id)) {
                    return response()->json(["error" => "Doctor ID is missing"], 400);
                }

                if (empty($profile->drchrono_patient_id)) {
                    return response()->json(["error" => "Patient ID is missing"], 400);
                }

                $documentPath = $byte["link"];
                $fixedUrl = str_replace("//", "/", $documentPath);
                $fixedUrl = preg_replace("#^https:/#", "https://", $fixedUrl);

                // Use fopen for remote URLs
                $documentContent = fopen($fixedUrl, 'r')??"";
         
                if (!$documentContent) {
                    return response()->json(["error" => "Unable to read file: " . $fixedUrl], 400);
                }

      

                // Prepare the request options
                // $options = [
                //     [
                //         'name' => 'date',
                //         'contents' => $currentDate
                //     ],
                //     [
                //         'name' => 'description',
                //         'contents' => $byte["name"] ?? ""
                //     ],
                //     [
                //         'name' => 'doctor',
                //         'contents' => $doctor->drchrono_id ?? ""
                //     ],
                //     [
                //         'name' => 'document',
                //         'contents' => $documentContent,
                //         'filename' => 'document.jpg' // Change this to the correct file name as needed
                //     ],
                //     [
                //         'name' => 'patient',
                //         'contents' => $profile->drchrono_patient_id ?? ""
                //     ],
                // ];
             
                // // Send the request
                // try {
                //     Log::info('Request Options: ', $options);
                //     $res = $client->request('POST', 'https://app.drchrono.com/api/documents', [
                //         'headers' => [
                //             'Authorization' => 'Bearer ' . $token->access_token,
                //             // 'Content-Type' => 'multipart/form-data', // Not needed; Guzzle handles it
                //         ],
                //         'multipart' => $options
                //     ]);

                //     $updatedata = json_decode($res->getBody(), true);
                //     Log::info('Sending patient data to DrChrono API: ', $updatedata);

                // } catch (\GuzzleHttp\Exception\RequestException $e) {
                //     // Log the error response
                //     Log::error('Error uploading document: ' . $e->getMessage(), [
                //         'response' => $e->getResponse() ? (string)$e->getResponse()->getBody() : null,
                //         'request' => $e->getRequest()->getBody() // Log the request body for context
                //     ]);
                //     return response()->json(["error" => "Failed to upload document: " . $e->getMessage()], 500);
                // }


                                
                        $client = new Client();
                        $headers = [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $token->access_token,
                        'Cookie' => '_cfuvid=j9Y3D.NKlXOrxCH1TvKef1o3x3SaaGpam68qKw76yS8-1729219282125-0.0.1.1-604800000; csrftoken=ob45z5sCnc6NV2renI0uGmxSP0CB46pj1inO1VeBsnVB2oclOxFUQEryhHLHwtol'
                        ];
                        $options = [
                        'multipart' => [
                            [
                            'name' => 'date',
                            'contents' =>$currentDate
                            ],
                            [
                            'name' => 'description',
                            'contents' =>$byte["name"]??""
                            ],
                            [
                            'name' => 'doctor',
                            'contents' =>$doctor->drchrono_id ??""
                            ],
                            [
                            'name' => 'document',
                            'contents' =>$documentContent??"",
                            //'filename' => '.pdf',
                            ],
                            [
                            'name' => 'patient',
                            'contents' =>$profile->drchrono_patient_id??""
                            ]
                        ]];
                        $request = new Req('POST', 'https://app.drchrono.com/api/documents', $headers);
                        $res = $client->sendAsync($request, $options)->wait();
                    
                            $updatedata = json_decode($res->getBody(), true);
                        Log::info('Sending patient data to DrChrono API: ', $updatedata);




            }
        }


        // $client = new Client();
        // $headers = [
        // 'Accept' => 'application/json',
        // 'Authorization' => 'Bearer ' . $token->access_token,
        // 'Cookie' => '_cfuvid=j9Y3D.NKlXOrxCH1TvKef1o3x3SaaGpam68qKw76yS8-1729219282125-0.0.1.1-604800000; csrftoken=ob45z5sCnc6NV2renI0uGmxSP0CB46pj1inO1VeBsnVB2oclOxFUQEryhHLHwtol'
        // ];
        // $options = [
        // 'multipart' => [
        //     [
        //     'name' => 'date',
        //     'contents' =>"2024-10-18"
        //     ],
        //     [
        //     'name' => 'description',
        //     'contents' =>"consent form"
        //     ],
        //     [
        //     'name' => 'doctor',
        //     'contents' =>"500993"
        //     ],
        //     [
        //     'name' => 'document',
        //     'contents' => fopen("https://ik.imagekit.io/mhtpe5cvo/new-file_Zp_vBPqQa.pdf", 'r') ,
        //     //'filename' => '.pdf',
        //     ],
        //     [
        //     'name' => 'patient',
        //     'contents' =>"123487647"
        //     ]
        // ]];
        // $request = new Req('POST', 'https://app.drchrono.com/api/documents', $headers);
        // $res = $client->sendAsync($request, $options)->wait();
     
        //     $updatedata = json_decode($res->getBody(), true);
        // Log::info('Sending patient data to DrChrono API: ', $updatedata);



        return response()->json(["success" => $finish], 200);
    } else {
        return response()->json(["error" => "No data provided"], 400);
    }
}



public function testcheck(){
    $array = array(["name"=>"hello", "surname"=>"Okpeku"], ["name"=>"hello", "surname"=>"Okpeku"], ["name"=>"hello", "surname"=>"Okpeku"], ["name"=>"hello last", "surname"=>"Okpeku last"]);
$lastElement = end($array); // Get the last element of the array
//echo json_encode($lastElement);
$answer = "";
foreach ($array as $value) {
    if ($value["name"] === $lastElement["name"]) {
        $answer .= $value["name"];
    }
}

echo $answer;
}

}
