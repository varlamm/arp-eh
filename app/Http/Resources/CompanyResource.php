<?php

namespace Xcelerate\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->logo,
            'logo_path' => $this->logo_path,
            'transparent_logo' => $this->transparent_logo,
            'transparent_logo_path' => $this->transparent_logo_path,
            'unique_hash' => $this->unique_hash,
            'owner_id' => $this->owner_id,
            'slug' => $this->slug,
            'address' => $this->when($this->address()->exists(), function () {
                return new AddressResource($this->address);
            }),
            'roles' => RoleResource::collection($this->roles)
        ];
    }
}
