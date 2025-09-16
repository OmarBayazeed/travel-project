<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $fillable = [
        'payable_id',
        'payable_type',
        'provider',
        'paypal_order_id',
        'status',
        'amount',
        'currency',
    ];

    public function payable()
    {
        return $this->morphTo();
    }

}
