<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;
    protected $table = 'tours';
    protected $fillable = [
        'title',
        'description',
        'location',
        'duration',
        'price',
        'currency',
        'start_date',
        'end_date',
        'available_seats',
        'includes',
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
            : (float) $this->price; // adjust per model's base price
    }
}
