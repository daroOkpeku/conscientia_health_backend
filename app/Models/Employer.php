<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    protected $fillable =[
       "employer_name",
       "employer_state",
        "employer_city",
        "employer_zip_code",
         "employer_address"
    ];
}
