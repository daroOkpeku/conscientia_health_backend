<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppToken extends Model
{
    use HasFactory;


    protected $fillable = [
        "code",
        'access_token',
        'refresh_token',
        'expires_timestamp',
    ];
}
