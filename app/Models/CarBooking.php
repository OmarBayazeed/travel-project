<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarBooking extends Model
{
    use HasFactory;
    protected $table = 'car_bookings';
    protected $fillable = [
        'car_id',
        'client_id',
        'pickup_date',
        'dropoff_date',
        'pickup_location',
        'dropoff_location',
        'total_days',
        'total_price',
        'currency',
        'status',
        'payment_status',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
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
