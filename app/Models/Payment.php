<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'tx_ref',
        'order_id',
        'amount',
        'currency',
        'status',
        'payment_type',
        'customer_email',
        'customer_phone',
        'flw_id',
        'raw_response',
    ];
}
