<?php

namespace App\Http\Requests\Regional\SeasonRegional;

use Illuminate\Foundation\Http\FormRequest;

class SeasonRegionalRequest extends FormRequest
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
            'StateCode' => 'required',
            'SeasonID' => 'required',
            'SeasonStartWeek' => 'required',
            'SeasonEndWeek' => 'required',
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
            'StateCode.required' => 'Please select state.',
            'SeasonID.required' => 'Please select season.',
            'SeasonStartWeek.required' => 'Please select season start seek.',
            'SeasonEndWeek.required' => 'Please select season end week.',
        ];
    }
}
