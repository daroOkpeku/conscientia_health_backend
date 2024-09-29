<?php

namespace App\Http\Controllers;

use App\Http\Repository\Contracts\PostRespositoryinterface;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\PrimaryRequest;
use App\Http\Requests\ProfileCreateRequest;
use Illuminate\Http\Request;

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

    public function primary_insurance(PrimaryRequest $request){
     return $this->postmethod->primary_insurance_create($request);
    }

    public function uploadprofileimage(Request $request){
     return $this->postmethod->uploadprofileimage($request);
    }
}
