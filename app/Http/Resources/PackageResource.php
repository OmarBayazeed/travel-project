<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'duration' => $this->duration,
            'price' => $this->price,
            'currency' => $this->currency,
            'accommodation' => $this->accommodation,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'transport' => $this->transport,
            'meals' => $this->meals,
            'images' => $this->images,
            'is_on_offer'   => $this->is_on_offer,
            'offer_price'   => $this->offer_price,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
