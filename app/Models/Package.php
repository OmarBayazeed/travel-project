<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $table = 'packages';
    protected $fillable = [
        'title',
        'description',
        'duration',
        'price',
        'currency',
        'accommodation',
        'start_date',
        'end_date',
        'transport',
        'meals',
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
