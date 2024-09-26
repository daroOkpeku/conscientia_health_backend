<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Online_booking_request extends FormRequest
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
            'firstname' => "required|string",
            'lastname' => "required|string",
            'state' => 'required|string',
            'doctor' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string|regex:/^[0-9+ ]+$/',
            'comment' => "required|string",  // Corrected from 'sting' to 'string'
            "captcha.captcha" => "captcha",
            "visit_type" => "required|string",
            'mean_payment'=>"required|string"
        ];
        
    }
}
