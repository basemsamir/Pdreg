<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DateIntervalValidationRequest extends Request
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
            //
            'duration_from' => 'required_if:date_selection,date_selected|date',
            'duration_to' => 'required_if:date_selection,date_selected|date',
        ];
    }

    public function messages()
    {
        return [
            'duration_from.date' => 'تاريخ من يجب أن يكون تاريخ فقط',
            'duration_from.required_if' => 'يجب ادخال تاريخ الفترة من',
            'duration_to.date' => 'تاريخ ألى يجب أن يكون تاريخ فقط',
            'duration_to.required_if' => 'يجب ادخال تاريخ الفترة الى',
        ];
    }
}
