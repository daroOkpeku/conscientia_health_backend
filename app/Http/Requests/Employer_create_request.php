<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Employer_create_request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "employer_name"=>"required|string",
            "employer_state"=>"required|string",
             "employer_city"=>"required|string",
             "employer_zip_code"=>"required|string",
              "employer_address"=>"required|string",
              "user_id"=>"required|integer",
              "id"=>"integer|nullable"
        ];
    }
}
