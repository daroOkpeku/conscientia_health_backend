<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "first_name"=>$this->first_name,
            "last_name"=>$this->last_name,
            "middle_name"=>$this->middle_name,
            "nick_name"=>$this->nick_name,
            "email"=>$this->email,
            "state"=>$this->state,
            "patient_photo"=>$this->patient_photo,
            "date_of_birth"=>$this->date_of_birth,
            "home_phone"=>$this->home_phone,
            "office_phone"=>$this->office_phone,
            "cell_phone"=>$this->cell_phone,
            "address"=>$this->address,
            "zip_code"=>$this->zip_code,
            "gender"=>$this->gender,
            "race"=>$this->race,
            "ethnicity"=>$this->ethnicity,
            "chart_id"=>$this->chart_id,
            "doctor"=>$this->doctor,
            "patient_status"=>$this->patient_status,
            "preferred_language"=>$this->preferred_language,
            'user_id'=>$this->user_id,
            "userData"=>UserResource::make($this->whenLoaded('userData'))
        ];
    }
}
