<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;


    protected $fillable = [

        'firstname',
        'lastname',
        'state',
        'doctor',
        'email',
        'phone',
        'comment',
        "visit_type",
        "code",
        "is_used",
        "country",
        "legal_sex",
        "dob",
        "schedule_time",
        "mean_payment"
    ];


}
