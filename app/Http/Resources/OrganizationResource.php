<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'uuid'              => $this->uuid,
            'name'              => $this->name,
            'logo'              => $this->image_path ? [config('app.project_url') . $this->image_path] : null,
            'email'             => $this->email,
            'email_verified_at' => $this->email_verified_at
        ];
    }
}
