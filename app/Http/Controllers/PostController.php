<?php

namespace App\Http\Controllers;

use App\Http\Repository\Contracts\PostRespositoryinterface;
use App\Http\Requests\Admin_profile_Create_Request;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ConsentRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\CreateAdminRequest;
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
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public $postmethod;
    public function __construct(PostRespositoryinterface $postinterface)
    {
        $this->postmethod = $postinterface;
    }

    public function userdata($id){
        try {
           $user = User::find($id);
           return $user;
        } catch (\Throwable $th) {
           return  "do not exist";
        }
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

    public function consent_upload(ConsentRequest $request){
        return $this->postmethod->consent_upload($request);
    }

    public function createadminuser(CreateAdminRequest $request){
        $data = $this->userdata($request->id);
        if(Gate::allows("check-admin", $data)){
        return $this->postmethod->createadminuser($request);
        }else{
            return response()->json(["error"=>"you don't have access to this api"],200);
        }
    }

    public function createadminuseredit(CreateAdminRequest $request){
        $data = $this->userdata($request->id);
        if(Gate::allows("check-admin", $data)){
        return $this->postmethod->createadminuseredit($request);
        }else{
            return response()->json(["error"=>"you don't have access to this api"],200);
        }
    }

    public function admin_profile_create(Admin_profile_Create_Request $request){
        $data = $this->userdata($request->id);
        if(Gate::allows("check-admin", $data)){
            return $this->postmethod->admin_profile_create($request);
        }else{
            return response()->json(["error"=>"you don't have access to this api"],200);
        }
    }

}
