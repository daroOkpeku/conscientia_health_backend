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
public function personal_signed($request);
public function consent_upload($request);
public function createadminuser($request);
public function createadminuseredit($request);
public function admin_profile_create($request);
public function send_message($request);
public function updateTypingStatus($request);
public function update_message($request);
}
