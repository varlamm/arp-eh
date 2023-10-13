<?php

namespace Crater\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'contact_name' => $this->contact_name,
            'company_name' => $this->company_name,
            'website' => $this->website,
            'enable_portal' => $this->enable_portal,
            'currency_id' => $this->currency_id,
            'facebook_id' => $this->facebook_id,
            'google_id' => $this->google_id,
            'github_id' => $this->github_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'avatar' => $this->avatar,
            'is_owner' => $this->isOwner(),
            'creator_id' => $this->creator_id,
            'zoho_role_id_crater' => $this->zoho_role_id_crater,
            'zoho_role_id' => $this->zoho_role_id,
            'zoho_users_id' => $this->zoho_users_id,
            'zoho_profile_id' => $this->zoho_profile_id,
            'zoho_profile_name' => $this->zoho_profile_name,
            'zoho_status_active' => $this->zoho_status_active,
            'zoho_sync' => $this->zoho_sync,
            'roles' => $this->roles,
            'formatted_created_at' => $this->formattedCreatedAt,
            'currency' => $this->when($this->currency()->exists(), function () {
                return new CurrencyResource($this->currency);
            }),
            'companies' => $this->when($this->companies()->exists(), function () {
                return CompanyResource::collection($this->companies);
            })
        ];
    }
}
