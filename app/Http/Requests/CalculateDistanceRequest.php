<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculateDistanceRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true; 
    }
 
    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'The latitude of the origin point is required.',
            'latitude.numeric' => 'The latitude must be a valid numeric value.',
            'latitude.between' => 'The latitude must be between -90 and 90 degrees.',
            
            'longitude.required' => 'The longitude of the origin point is required.',
            'longitude.numeric' => 'The longitude must be a valid numeric value.',
            'longitude.between' => 'The longitude must be between -180 and 180 degrees.'
        ];
    }
    
    
}
