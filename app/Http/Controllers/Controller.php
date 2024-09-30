<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Crypt;
use ImageKit\ImageKit;
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


    public function uploadImage($image)
    {

        $imageKit = new ImageKit(
            "public_ubzgMmE6xfs3M3PhH7b0RdPCNVs=",
            "private_i8k1ateHiH63X3zO4lxSNn6bLWk=",
            "https://ik.imagekit.io/mhtpe5cvo"
        );
        $fileContent = $image;
        $uploadFile = $imageKit->uploadFile([
            'file' => base64_encode($fileContent),
            'fileName' => 'new-file'
        ]);
        return $uploadFile;
        //return $uploadFile->result->url;
    }
    // $imglink = $this->uploadImage($fileContent);
    // $imglink->result->url;
}
