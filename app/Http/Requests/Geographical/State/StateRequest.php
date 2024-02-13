<?php

namespace App\Http\Requests\Geographical\State;

use Illuminate\Foundation\Http\FormRequest;

class StateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'Name' => 'required|min:5|max:100',
            'CountryCode' => 'required',
            'StateCode' => 'required',
        ];
    }

    /**
     * Get the validation error messages that apply to the request
     *
     * @return array
     */
    public function messages()
    {
        return [
            'Name.required' => 'Please enter name.',
            'Name.min' => 'You must have to enter at least 5 letters.',
            'Name.max' => 'Please enter maximun 100 letters only.',
            'CountryCode.required' => 'Please enter Country Code.',
            'StateCode.required' => 'Please enter State Code.',
        ];
    }
}