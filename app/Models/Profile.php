<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    // formdata.append("race", race?.value)
    // formdata.append("ethnicity", ethnicity?.value)
    protected $fillable = [
      "first_name",
      "last_name",
      "middle_name",
      "nick_name",
      "email",
      "state",
      "patient_photo",
      "date_of_birth",
      "home_phone",
      "office_phone",
      "cell_phone",
      "address",
      "zip_code",
      "gender",
      "race",
      "ethnicity",
      "chart_id",
      "doctor",
      "patient_status",
      "preferred_language",
      'user_id',
      'drchrono_patient_id',
      "push_to_drchrono",
      "onpatient_push_drchrono"
    ];

    public function userData(){
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function primaryinsurancedata(){
        return $this->hasOne(Primary_insurance::class, "user_id", "user_id");
    }

    public function secondaryinsurancedata(){
     return $this->hasOne(Secondary_insurance::class, "user_id", "user_id");
    }

    public function employeedata(){
        return $this->hasOne(Employer::class, "user_id", "user_id");
    }

    public function emergencydata(){
        return $this->hasOne(Emergency_contact::class, "user_id", "user_id");
    }

    public function responsibleparty(){
        return $this->hasOne(Responsible_party::class, "user_id", "user_id");
    }
}
