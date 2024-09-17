<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Crypt;
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private function generateToken ($value) {

        $data = [
            'value' => $value,
            'expiry' => now()->addMinutes(5)->timestamp
        ];

        return Crypt::encrypt($data);

    }

    private function decryptToken ($token) {

        return Crypt::decrypt($token);

    }
}
