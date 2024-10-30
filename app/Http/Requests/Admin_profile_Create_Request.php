<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Admin_profile_Create_Request extends FormRequest
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
            "email"=>"required|email",
            "state"=>"regex:/^[a-zA-Z0-9+\-._(): ]+$/|nullable",
            "home_phone"=>"required|string",
            "office_phone"=>"required|string",
            "cell_phone"=>"required|string",
            "gender"=>"required|regex:/^[a-zA-Z0-9+\-._(): ]+$/",
            "id"=>"required|string"
            // "chart_id"=>"required|string",
            // "patient_status"=>"required|string",
            // "preferred_language"=>"required|string",
        ];
    }
}
