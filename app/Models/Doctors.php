<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctors extends Model
{
    use HasFactory;

    protected $fillable = [
        'drchrono_id',
        'first_name',
        'last_name',
        'email',
        'specialty',
        'job_title',
        'suffix',
        'website',
        'home_phone',
        'office_phone',
        'cell_phone',
        'country',
        'timezone',
        'npi_number',
        'group_npi_number',
        'practice_group',
        'practice_group_name',
        'profile_picture',
        'is_account_suspended'
    ];
}
