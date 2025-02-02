<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'color' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The location name is required.',
            'name.string' => 'The location name must be a string.',
            'name.max' => 'The location name must not exceed 255 characters.',

            'latitude.required' => 'The latitude is required.',
            'latitude.numeric' => 'The latitude must be a numeric value.',
            'latitude.between' => 'The latitude must be between -90 and 90.',

            'longitude.required' => 'The longitude is required.',
            'longitude.numeric' => 'The longitude must be a numeric value.',
            'longitude.between' => 'The longitude must be between -180 and 180.',
        ];
    }
}
