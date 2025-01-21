<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthCenterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'phone' => 'required',
            'line_1' => 'required',
            'line_2' => 'nullable',
            'postcode' => 'required',
            'city' => 'required',
            'county' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'active' => 'nullable|boolean'
        ];
    }

    public function messages()
    {
        return [
            'latitude.numeric' => 'The latitude must be a valid number.',
            'longitude.numeric' => 'The longitude must be a valid number.',
        ];
    }
}
