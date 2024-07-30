<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
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
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_of_email_verification'  => ['required'],
            // 'password' => ['required'],
            'password' => ['required','string','min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
            'role'     => ['in:user,admin'],
        ];
    }

    public function messages(){
        return [
            'name.required'      => '*The name field is mandatory.',
            'email.required'     => '*The email address is required.',
            'no_of_email_verification.required' => '*Please select atleast one.',
            'email.email'        => '*The email address must be a valid email format.',
            'email.unique'       => '*This email address is already registered.',
            'role.in'            => '*The role must be either user or admin.',
            'password.required'  => '*The password field is required.',
            'password.min'       => '*The password must be at least 8 characters long.',
            // 'password.confirmed' => 'The password confirmation does not match.',
            'password.regex'     => '*The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ];
    }
}
