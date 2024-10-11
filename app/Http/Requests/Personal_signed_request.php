<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Personal_signed_request extends FormRequest
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

        // "name",
        // "image",
        // "user_id"
        return [
            "image"=>"required|string",
            "name"=>"required|string",
            "user_id"=>"required|integer"
        ];
    }
}
