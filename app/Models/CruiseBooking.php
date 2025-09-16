<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CruiseBooking extends Model
{
    use HasFactory;
    protected $table = 'cruise_bookings';
    protected $fillable = [
        'cruise_id',
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

    public function cruise()
    {
        return $this->belongsTo(Cruise::class, 'cruise_id');
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
