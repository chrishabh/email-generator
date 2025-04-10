<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicBulkUploadFormRequest extends FormRequest
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
            'file' => 'required|file|mimes:csv|max:2048', // max 2MB
        ];
    }

    public function messages(){
        return [
            'file.required' => 'Please upload a CSV file.',
            'file.mimes' => 'Only CSV files are allowed.',
        ];
    }
}
