<?php

namespace App\Http\Requests\Regional\MonthlyTravelTime;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyTravelTimeRequest extends FormRequest
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
            'RegionID' => 'required',
            'Month' => 'required',
            'UnitType' => 'required',
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
            'RegionID.required' => 'Please select region.',
            'Month.required' => 'Please select month.',
            'UnitType.required' => 'Please select unit.',
        ];
    }
}
