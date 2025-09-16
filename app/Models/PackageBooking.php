<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageBooking extends Model
{
    use HasFactory;
    protected $table = 'package_bookings';
    protected $fillable = [
        'package_id',
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

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
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
