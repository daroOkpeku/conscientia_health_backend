<?php

namespace App\Http\Repository;

use App\Http\Repository\Contracts\PostRespositoryinterface;
use App\Jobs\ProcessContact;
use App\Jobs\ProcessPrimary_insurance;
use App\Jobs\Processprofile_create;
use App\Jobs\Processprofile_edit;
use App\Models\Profile;

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


public function profile_create($request){
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
    "doctor"=>$request->doctor,
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
        "chart_id"=>$request->chat_id,
        "doctor"=>$request->doctor,
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


public function primary_insurance_create($request)
{
    $data = [
        "insurance_group_number"=>$request->insurance_group_number,
        "insurance_company"=>$request->insurance_company,
        "insurance_payer_id"=>$request->insurance_payer_id,
        "insurance_plan_type"=>$request->insurance_plan_type,
        "insurance_image_font"=>$request->insurance_image_font,
        "insurance_image_back"=>$request->insurance_image_back
    ];

    ProcessPrimary_insurance::dispatchSync($data);
    return response()->json(["success"=>"your Primary Insurance has been Created"]);
}


public function uploadprofileimage($request){
    $proflie = Profile::find($request->id);
    $fileContent = file_get_contents($request->file('image')->getRealPath());
    $imglink = $this->uploadprofileimage($fileContent);
    if($proflie){
        // $imglink = $this->uploadImage($fileContent); 
      
      $proflie->update([
        "patient_photo"=>$imglink
      ]);
    }else{
        Profile::create([
          "patient_photo"=>$imglink,
          "user_id"=>$request->user_id
        ]);
    }
}

}
