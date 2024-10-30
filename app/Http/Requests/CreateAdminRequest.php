<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminRequest extends FormRequest
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
            "firstname"=>"required|string",
            "lastname"=>"required|string",
            "state"=>"required|string",
            "doctor"=>"string|nullable",
            "email"=>"required|email",
            "gender"=>"required|string",
            "dob"=>"required|string",
            "user_type"=>"required|string",
            "id"=>"string|nullable",
            "user_id"=>"string|nullable"
        ];
    }
}
