<?php


namespace App\Http\Repository\Contracts;


interface PostRespositoryinterface{

public function contact($request);
public function profile_create($request);
public function profile_edit($request);
public function primary_insurance_create($request);
public function primary_insurance_edit($request);
public function uploadprofileimage($request);
public function change_password($request);
public function secondary_insurance_create($request);
public function secondary_insurance_edit($request);
public function employee_create($request);
public function employee_edit($request);
public function responsible_party_create($request);
public function responsible_party_edit($request);
public function emergency_contact_create($request);
public function emergency_contact_edit($request);
}
