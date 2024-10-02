<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Responsible_Party_Create_request extends FormRequest
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
            "responsible_party_name"=>"required|string",
            "responsible_party_email"=>"required|string",
            "responsible_party_phone"=>"required|string",
            "responsible_party_relation"=>"required|string",
            "user_id"=>"integer|nullable"
        ];
    }
}
