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
            'uuid' => $this->uuid,
            'name' => $this->name,
            'default_logo' => [
                'initial' => $this->name[0].$this->name[1],
                'bg_color' => ($metaData = $this->metaData->where('key', 'default_profile_logo_bg')->first()) ? $metaData->value : '',
            ],
            'logo' => $this->image_path ? [config('app.url').$this->image_path] : null,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
        ];
    }
}
