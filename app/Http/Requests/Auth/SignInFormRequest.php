<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignInFormRequest extends FormRequest
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
            'emailL'    => ['required', 'string', 'email'],
            'passwordL' => ['required','string'],
        ];
    }

    public function messages(){
        return [ 
            'emailL.required'     => '*The email address is required.', 
            'emailL.email'        => '*The email address must be a valid email format.', 
            'passwordL.required'  => '*The password field is required.',  
        ];
    }
}
