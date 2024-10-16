<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class DoctorCrons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctor:crons';

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

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.drchrono.com/api/doctors',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization:Bearer RCuBUatfnxaMsDpy4X6oSSWjH5NwwU',
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response, true);
        $doctors = $data['results'];
        foreach ($doctors as $doctor) {
            DB::table('doctors')->insert([
            'drchrono_id'=>$doctor['id'],
            'first_name'=>$doctor['first_name'],
            'last_name'=>$doctor['last_name'],
            'email'=>$doctor['email'],
            'specialty'=>$doctor['specialty'],
            'job_title'=>$doctor['job_title'],
            'suffix'=>$doctor['suffix'],
            'website'=>$doctor['website'],
            'home_phone'=>$doctor['home_phone'],
            'office_phone'=>$doctor['office_phone'],
            'cell_phone'=>$doctor['cell_phone'],
            'country'=>$doctor['country'],
            'timezone'=>$doctor['timezone'],
            'npi_number'=>$doctor['npi_number'],
            'group_npi_number'=>$doctor['group_npi_number'],
            'practice_group'=>$doctor['practice_group'],
            'practice_group_name'=>$doctor['practice_group_name'],
            'profile_picture'=>$doctor['profile_picture'],
            'is_account_suspended'=>$doctor['is_account_suspended']
           ]);

        }


    }


}
