<?php

namespace Xcelerate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyFieldRequest extends FormRequest
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
            'column_name' => 'required',
            'label' => 'required',
            'table_name' => 'required',
            'column_type' => 'required',
            'is_required' => 'required|boolean',
            'field_type' => 'required',
            'is_unique' => 'required',
            'visiblity' => 'required',
            'listing_page' => 'required',
            'order_listing_page' => 'required',
            'order_form_page' => 'required',
            'options' => 'array',
        ];
    }
}
