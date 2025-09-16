<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarBookingResource extends JsonResource
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
            'pickup_date' => $this->pickup_date,
            'dropoff_date' => $this->dropoff_date,
            'pickup_location' => $this->pickup_location,
            'dropoff_location' => $this->dropoff_location,
            'total_days' => $this->total_days,
            'total_price' => $this->total_price,
            'currency' => $this->currency,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'car' => new CarResource($this->car),
        ];
    }
}
