<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResourcedata;
use App\Http\Resources\UserResouresShow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    protected PendingRequest $http;

    public function __construct()
    {
        $this->http = Http::baseUrl("http://ip-api.com");
    }

    
    public function userlist(){
        $user = User::where(["user_type"=>'user'])->with(['SingleOne'])->get();
        if($user){
          //return response()->json([$user]);  
          return  ProfileResourcedata::collection($user)->additional(["success"=>true]);
        }
        }

        public function get_customer_list(){
            $user = User::where(["user_type"=>'customer_care'])->get();
            if($user){
                return  UserResouresShow::collection($user)->additional(['success'=>true]);
            }
        }

     
     
    
    
        public function geoip($ip)
        {
            $response = $this->http->get("/json/{$ip}");
        
            if ($response->successful()) {
                return response()->json(['success' => $response->json()]);
            }
        
            return response()->json(['error' => 'Request failed'], 200);
        }
    
}