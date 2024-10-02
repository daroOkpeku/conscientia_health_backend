<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Primary_insurance extends Model
{
    use HasFactory;

    protected $fillable =[
        "photo_front",
         "photo_back",
         "insurance_group_number",
          "insurance_company",
            "insurance_payer_id",
            "insurance_plan_type",
            "user_id"
    ];
}
