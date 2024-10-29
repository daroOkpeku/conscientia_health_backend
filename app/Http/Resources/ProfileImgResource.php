<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileImgResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          "patient_photo"=>str_contains($this->patient_photo, 'imagekit')?$this->patient_photo:'https://ik.imagekit.io/9nikkw38wtz/_Pngtree_no%20image%20vector%20illustration%20isolated_4979075_UewvJXbrf.png?updatedAt=1714304941277'
        ];
    }
}
