<?php

namespace Xcelerate\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseCategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
