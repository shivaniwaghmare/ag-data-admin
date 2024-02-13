<?php

namespace App\Http\Requests\Regional\Terrain;

use Illuminate\Foundation\Http\FormRequest;

class TerrainRequest extends FormRequest
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
            'TerrainType' => 'required',
            'MinPerKm' => 'required',
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
            'TerrainType.required' => 'Please enter terrain type.',
            'MinPerKm.required' => 'Please enter minute per kilometer.',
        ];
    }
}
