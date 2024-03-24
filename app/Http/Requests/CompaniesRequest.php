<?php

namespace Xcelerate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompaniesRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('companies'),
                'string'
            ],
            'company_url' => [
                'nullable'
            ],
            'invoice_url' => [
                'nullable'
            ],
            'currency' => [
                'required'
            ],
            'address.name' => [
                'nullable',
            ],
            'address.address_street_1' => [
                'nullable',
            ],
            'address.address_street_2' => [
                'nullable',
            ],
            'address.city' => [
                'nullable',
            ],
            'address.state' => [
                'nullable',
            ],
            'address.country_id' => [
                'required',
            ],
            'address.zip' => [
                'nullable',
            ],
            'address.phone' => [
                'nullable',
            ],
            'address.fax' => [
                'nullable',
            ],
            // New validations for update Company API
            'subdomain_name' => [],
            'app_sever_name' => [],
            'new_database' => [],
            'sever_info' => [],
        ];
    }

    public function getCompanyPayload()
    {
        return collect($this->validated())
            ->only([
                'name',
                'company_url',
                'invoice_url'
            ])
            ->merge([
                'owner_id' => $this->user()->id,
                'slug' => Str::slug($this->name)
            ])
            ->toArray();
    }
}
