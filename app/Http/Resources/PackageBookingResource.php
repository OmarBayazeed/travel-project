<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageBookingResource extends JsonResource
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
            'number_of_guests' => $this->number_of_guests,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'nationality' => $this->nationality,
            'special_requests' => $this->special_requests,
            'total_price' => $this->total_price,
            'currency' => $this->currency,
            'created_at' => $this->created_at,
            'package' => new PackageResource($this->package),
        ];
    }
}
