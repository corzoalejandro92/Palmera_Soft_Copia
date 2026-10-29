<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCrmSimpleRequest extends FormRequest
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
            'crm_id'=>'required',
            'office_id'=>'required',
        ];
    }
    public function attributes()
    {
        return [
            'crm_id'=>'Cliente',
            'office_id'=>'Sucursal',
        ];
    }
}
