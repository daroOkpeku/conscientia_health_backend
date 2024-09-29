<?php


namespace App\Http\Repository\Contracts;


interface PostRespositoryinterface{

public function contact($request);
public function profile_create($request);
public function primary_insurance_create($request);
public function uploadprofileimage($request);
}
