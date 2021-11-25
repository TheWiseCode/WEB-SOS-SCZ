<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkShiftRequest extends FormRequest
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
            'officer_id'=>'required',
            'schedule_id'=>'required',
            'vehicle_id'=>'required',
            'shift_starts'=>'required',
            'shift_end'=>'required',
        ];
    }
}
