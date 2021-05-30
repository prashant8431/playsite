<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'user_name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
            'contact_no' => 'required|numeric',
            'bookie_id' => 'required',
            // 'role' => 'required',
        ];
    }
}
