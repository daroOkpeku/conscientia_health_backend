<?php

namespace App\Http\Controllers;

use App\Http\Repository\Contracts\PostRespositoryinterface;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\Emergency_request;
use App\Http\Requests\Employer_create_request;
use App\Http\Requests\Personal_signed_request;
use App\Http\Requests\PrimaryRequest;
use App\Http\Requests\ProfileCreateRequest;
use App\Http\Requests\Responsible_Party_Create_request;
use App\Http\Requests\UploadRequest;
use App\Models\Emergency_contact;
use App\Models\Primary_insurance;
use Illuminate\Http\Request;
use App\Models\Profile;
class PostController extends Controller
{
    public $postmethod;
    public function __construct(PostRespositoryinterface $postinterface)
    {
        $this->postmethod = $postinterface;
    }


    public function contact(ContactRequest $request){
      return  $this->postmethod->contact($request);
    }

    public function profile_create(ProfileCreateRequest $request){
     return $this->postmethod->profile_create($request);
    }

    public function profile_edit(ProfileCreateRequest $request){
        return $this->postmethod->profile_edit($request);
    }

    public function primary_insurance(PrimaryRequest $request){
     return $this->postmethod->primary_insurance_create($request);
    }

    public function uploadprofileimage(UploadRequest $request){
      return $this->postmethod->uploadprofileimage($request);
    }

    public function change_password(ChangePasswordRequest $request){
        return $this->postmethod->change_password($request);
    }

    public function primary_insurance_edit(PrimaryRequest $request){
        // PrimaryRequest
        // return response()->json($request->all());
        return $this->postmethod->primary_insurance_edit($request);
    }

    public function secondary_insurance_create(PrimaryRequest $request){
        return $this->postmethod->secondary_insurance_create($request);
    }

    public function secondary_insurance_edit(PrimaryRequest $request){
        return $this->postmethod->secondary_insurance_edit($request);
    }


    public function employer_create(Employer_create_request $request){
        return $this->postmethod->employee_create($request);
    }

    public function employer_edit(Employer_create_request $request){
        return $this->postmethod->employee_edit($request);
    }

    public function responsible_party_create(Responsible_Party_Create_request $request){
        return $this->postmethod->responsible_party_create($request);
    }

    public function responsible_party_edit(Responsible_Party_Create_request $request){
      return $this->postmethod->responsible_party_edit($request);
    }

    public function emergency_contact_create(Emergency_request $request){
        return $this->postmethod->emergency_contact_create($request);
    }

    public function emergency_contact_edit(Emergency_request $request){
        return $this->postmethod->emergency_contact_edit($request);
    }

    public function personal_signed(Personal_signed_request $request){
        return $this->postmethod->personal_signed($request);
    }

}
