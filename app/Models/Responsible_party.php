<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsible_party extends Model
{
    use HasFactory;

    // formdata.append("responsible_party_name", responsible_party_name);
    // formdata.append("responsible_party_email", responsible_party_email)
    // formdata.append("responsible_party_phone", responsible_party_phone)
    // formdata.append("responsible_party_relation", responsible_party_relation)

    protected $fillable = [
        "responsible_party_name",
        "responsible_party_email",
        "responsible_party_phone",
        "responsible_party_relation"
    ];
}
