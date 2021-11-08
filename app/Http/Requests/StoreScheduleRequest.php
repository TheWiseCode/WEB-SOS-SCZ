<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required',
            'start' => array(
                'required',
                'regex:([0-2][0-9]:[0-5][0-9])'
            ),
            'end' => array(
                'required',
                'regex:([0-2][0-9]:[0-5][0-9])'
            )
        ];
    }
}
