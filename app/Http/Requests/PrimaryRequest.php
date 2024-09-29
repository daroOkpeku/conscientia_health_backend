<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrimaryRequest extends FormRequest
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
            "insurance_group_number"=>"required|string",
            "insurance_company"=>"required|string",
            "insurance_payer_id"=>"required|regex:/^[a-zA-Z0-9+\-._(): ]+$/",
            "insurance_plan_type"=>"required|string",
            "insurance_image_font"=>"required|image",
            "insurance_image_font"=>"required|image"

        ];
    }
}
