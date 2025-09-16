<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CruiseResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'ship_name' => $this->ship_name,
            'stars' => $this->stars,
            'duration' => $this->duration,
            'route' => $this->route,
            'departure_day' => $this->departure_day,
            'departure_city' => $this->departure_city,
            'arrival_city' => $this->arrival_city,
            'cabin_types' => $this->cabin_types,
            'price_per_person' => $this->price_per_person,
            'currency' => $this->currency,
            'meals' => $this->meals,
            'facilities' => $this->facilities,
            'images' => $this->images,
            'is_on_offer'   => $this->is_on_offer,
            'offer_price'   => $this->offer_price,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
