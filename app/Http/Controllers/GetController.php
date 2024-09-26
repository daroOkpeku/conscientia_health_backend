<?php

namespace App\Http\Controllers;

use App\Models\AppToken;
use App\Models\Doctors;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class GetController extends Controller
{
    public function gencaptcha(){

        return response()->json(['image'=>Captcha::src()]);
    }

    public function verify_email(Request $request){
     $user = User::where(['email'=>$request->get('email'), 'firstname'=>$request->get('firstname')])->first();
     if($user){
      $user->update([
        'confirm_status'=>1
      ]);
      return response()->json(['success'=>'Your email has been confirmed', 'status'=>true], 200);
     }else{
        return response()->json(['error'=>'Email not Found', 'status'=>false], 404);
     }
    }


    public function decryptToken ($token) {

        return  response()->json(["success"=>Crypt::decrypt($token)]);

    }

    public function redirectdrchrono(){
        $redirectUri = env('DRCHRONO_REDIRECT_URI');
        $clientId = env('DRCHRONO_CLIENT_ID');
        // 'patients:summary:read patients:summary:write calendar:read calendar:write clinical:read clinical:write'
        $scopes ='labs:read labs:write messages:read messages:write patients:read patients:write patients:summary:read patients:summary:write settings:read settings:write tasks:read tasks:write user:read user:write billing:patient-payment:read billing:patient-payment:write billing:read billing:write calendar:read calendar:write clinical:read clinical:write'; // Example: 'clinical:read patients:read'

        // used this api to get code to generate access_token and refresh_token
        $redirectUriEncoded = urlencode($redirectUri);
        $clientIdEncoded = urlencode($clientId);
        $scopesEncoded = urlencode($scopes);


        $authorizationUrl = "https://drchrono.com/o/authorize/?redirect_uri={$redirectUriEncoded}&response_type=code&client_id={$clientIdEncoded}&scope={$scopesEncoded}";


        try {

            return response()->json([
                'body' => $authorizationUrl
            ],200);
        } catch (\Exception $e) {
            // Handle errors and exceptions
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }



    }

    //https://e2da-102-219-153-211.ngrok-free.app/api/auth/callback/drchrono?code=HeucgrQqBA4lPz3hKyYt3UycSPkb4O

    public function drchrono(Request $request){
          // Define the parameters required for the authorization URL
          $code = $request->get('code');
           $checktoken = AppToken::whereNotNull("code")->where("code", "!=", "")->first();
           if(!$checktoken){
            $apptoken = AppToken::create([
                "code"=>$code
              ]);
              return response()->json(['success'=>"successfull"],200);
           }else{
            $checktoken->update([
                "code"=>$code
            ]);
            return response()->json(['success'=>"successfull"],200);
           }
    }




    public function getAccessToken(Request $request){

        if (isset($getParams['error'])) {
            throw new Exception('Error authorizing application: ' . $getParams['error']);
        }
        $checktoken = AppToken::latest()->first();
        $redirectUri = env('DRCHRONO_REDIRECT_URI');
        $clientId = env('DRCHRONO_CLIENT_ID');
        $sercet = env("DRCHRONO_CLIENT_SECRET");
        // Prepare the data for the POST request
        $postData = [
            'code' =>$checktoken->code,
            'grant_type' => 'authorization_code',
            'redirect_uri' =>$redirectUri,
            'client_id' =>$clientId,
            'client_secret' =>$sercet,
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
            throw new Exception('Failed to retrieve access token. HTTP Status Code: ' . $httpCode );
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
        if($checktoken){
            $checktoken->update([
                'access_token'=>$accessToken,
                'refresh_token'=>$refreshToken,
                'expires_timestamp'=>$expiresTimestamp->format('Y-m-d H:i:s') . " UTC",
            ]);

            // Output or save the tokens and expiry information as needed
             return response()->json(["success"=>'successful']);
        }




}


public function showtest(Request $request){




}

public function list_doctors(Request $request){
  $doctors = Doctors::whereNotNull('job_title')
  ->where('is_account_suspended', 0)->where('first_name', '!=', 'Simbiat')
  ->inRandomOrder()
  ->take(3)
  ->get();
  $date = Carbon::now()->format('Y-m-d');
$client = new Client();
$headers = [
  'Content-Type' => 'application/json',
  'Accept' => 'application/json',
  'Authorization' => 'Bearer RCuBUatfnxaMsDpy4X6oSSWjH5NwwU',
];


$kod = array();
foreach ($doctors as  $doctor) {

    $request = new Req('GET', "https://app.drchrono.com/api/appointments?date={$date}&doctor={$doctor->drchrono_id}", $headers);
    $res = $client->sendAsync($request)->wait();
    $body = $res->getBody()->getContents();
    $data = json_decode($body, true);

 $jojo = [
    "doctor"=>$doctor->first_name." ".$doctor->last_name,
    "specialty"=>$doctor->specialty,
    "picture"=>$doctor->profile_picture,
    "day"=>$data['results'][0]['recurring_days']
 ];

 array_push($kod, $jojo);
}

  return response()->json(['success'=>$kod]);
}


public function doctor_availiable(){
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

public function state_age_check(Request $request){
    // $state = 'NJ';
    $doctors = Doctors::whereNotNull('job_title')
    ->where(['is_account_suspended'=>0, 'is_new_patient'=>1])
    ->where('age_start', '<=', $request->get('age'))
    ->where('age_end', '>=', $request->get('age'))
    ->whereJsonContains('states', $request->get('state'))
    ->get();

    if($doctors){
        return response()->json(["success"=>$doctors], 200);

    }
    else{
      return response()->json(['error'=>'we dont have any providers in the state you seleted please call us on +8778035342 or email us on info@conscientiahealth.com'],200);
    }

}
// ["FL","TX","NJ"]

}
