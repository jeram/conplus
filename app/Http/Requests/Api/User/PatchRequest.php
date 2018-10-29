<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class PatchRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->id,
            'password' => 'current_password:' . $this->id,
            'new_password' => 'required_with:password,'
        ];
    }

    public function messages()
    {
        return [
            'password.current_password' => 'Incorrect Password',
        ];  
    }
}
