<?php

namespace App\Http\Controllers;

use App\Http\Repository\Contracts\PostRespositoryinterface;
use App\Http\Requests\ContactRequest;
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
}
