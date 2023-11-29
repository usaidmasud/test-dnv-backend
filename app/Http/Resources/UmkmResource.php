<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UmkmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'city_id' => $this->city_id,
            'province_id' => $this->province_id,
            'province' => new ProvinceResource($this->province),
            'city' => new CityResource($this->city),
            'owner_name' => $this->owner_name,
            'contact' => $this->contact,
            'photos' => UmkmPhotoResource::collection($this->whenLoaded('photos')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
