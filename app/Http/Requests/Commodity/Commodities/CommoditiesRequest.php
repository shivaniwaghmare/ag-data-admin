<?php

namespace App\Http\Requests\Commodity\Commodities;

use Illuminate\Foundation\Http\FormRequest;

class CommoditiesRequest extends FormRequest
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
            'Name' => 'required',
            'CommodityGroupIndex' => 'numeric',
            'Logo' => 'required_without:Logo',
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
        ];
    }
}
