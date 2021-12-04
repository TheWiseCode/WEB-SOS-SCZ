<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmergencyRequest extends FormRequest
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
            'civilian_id'=>'required',
            'type' => 'required',
            'description' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'state' => 'required'
        ];
    }
}
