<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MedicalReportsRequest extends Request
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
            'duration_from' => 'date',
            'duration_to' => 'date|after:duration_from',
        ];
    }

    public function messages()
    {
        return [
            'duration_from.date' => 'تاريخ من يجب أن يكون تاريخ فقط',
            'duration_to.date' => 'تاريخ ألى يجب أن يكون تاريخ فقط',
            'duration_to.after' => ' حقل تاريخ ألى يجب أن يكون أكبر من التاريخ من ',
        ];
    
    }
}
