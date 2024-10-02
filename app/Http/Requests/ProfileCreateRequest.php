<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "first_name"=>"required|string",
            "last_name"=>"required|string",
            "middle_name"=>"required|regex:/^[a-zA-Z0-9+\-._(): ]+$/",
            "nick_name"=>"required|regex:/^[a-zA-Z0-9+\-._(): ]+$/",
            "email"=>"required|email",
            "state"=>"required|regex:/^[a-zA-Z0-9+\-._(): ]+$/",
            "date_of_birth"=>"required|string",
            "home_phone"=>"required|string",
            "office_phone"=>"required|string",
            "cell_phone"=>"required|string",
            "address"=>"required|string",
            "zip_code"=>"required|string",
            "gender"=>"required|regex:/^[a-zA-Z0-9+\-._(): ]+$/",
            // "chart_id"=>"required|string",
            "doctor"=>"string|nullable",
            // "patient_status"=>"required|string",
            // "preferred_language"=>"required|string",

              "race"=>"required|string",
              "ethnicity"=>"required|string"
        ];
    }
}
