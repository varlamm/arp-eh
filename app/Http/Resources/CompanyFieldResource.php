<?php

namespace Xcelerate\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyFieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'column_name' => $this->column_name,
            'label' => $this->label,
            'table_name' => $this->table_name,
            'column_type' => $this->column_type,
            'options' => $this->options,
            'boolean' => $this->boolean,
            'date' => $this->date,
            'time' => $this->time,
            'text' => $this->text,
            'price' => $this->price,
            'number' => $this->number,
            'date_time' => $this->date_time,
            'is_required' => $this->is_required,
            'crm_mapped_field' => $this->crm_mapped_field,
            'field_type' => $this->field_type,
            'is_unique' => $this->is_unique,
            'is_system' => $this->is_system,
            'visiblity' => $this->visiblity,
            'order_listing_page' => $this->order_listing_page,
            'order_form_page' => $this->order_form_page,
            'listing_page' => $this->listing_page,
            'default_value' => $this->default_value,
            'company' => $this->when($this->company()->exists(), function() {
                return new CompanyResource($this->company);
            })
        ];
    }
}
