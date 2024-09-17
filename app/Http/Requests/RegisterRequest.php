<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateToken;
class RegisterRequest extends FormRequest
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
          'firstname'=>'required|string',
          'lastname'=>'required|string',
          'email' =>"required|email|unique:users",
          'password' => 'required|min:8',
        //   'user_type' => 'required|string',
          'captcha.captcha' => 'captcha',
          'is_accepted'=>"required|accepted",
         // 'token' => ["required", new ValidateToken],
        ];
    }
}
