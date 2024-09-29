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
      'user_id'
    ];
}
