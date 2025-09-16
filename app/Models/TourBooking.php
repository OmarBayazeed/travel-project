<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourBooking extends Model
{
    use HasFactory;
    protected $table = 'tour_bookings';
    protected $fillable = [
        'tour_id',
        'client_id',
        'full_name',
        'phone',
        'nationality',
        'special_requests',
        'number_of_guests',
        'total_price',
        'currency',
        'payment_status',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }
}
