<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cruise extends Model
{
    use HasFactory;
    protected $table = 'cruises';
    protected $fillable = [
        'title',
        'description',
        'ship_name',
        'stars',
        'duration',
        'route',
        'departure_day',
        'departure_city',
        'arrival_city',
        'cabin_types',
        'price_per_person',
        'currency',
        'meals',
        'facilities',
        'images',
        'is_active',
        'is_on_offer',
        'offer_price',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_on_offer'    => 'boolean',
        'images'       => 'array',  // 👈 important
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFinalPriceAttribute(): ?float
    {
        return ($this->is_on_offer && $this->offer_price !== null)
            ? (float) $this->offer_price
            : (float) $this->price_per_person; // adjust per model's base price
    }
}
