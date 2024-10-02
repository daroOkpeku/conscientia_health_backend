<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency_contact extends Model
{
    use HasFactory;


    protected $fillable =[
        "emergency_contact_name",
        "emergency_contact_phone",
        "emergency_contact_relation",
        "user_id"
    ];
}
