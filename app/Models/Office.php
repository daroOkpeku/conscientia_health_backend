<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
      "drchrono_office_id",
      "country",
      "state",
      "city",
      "doctor_id",

    ];

}
