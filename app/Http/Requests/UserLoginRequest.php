<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function Rules()
    {
        return [
            'email'	        =>	'required|unique:users',
        ];
    }
    public function Messages()
    {
        return [
            'email.required'	=>	'Email already exists.',

        ];
    }
}
