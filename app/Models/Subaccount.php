<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subaccount extends Model
{
    protected $fillable = [
        'business_name',
        'business_email',
        'account_bank',
        'account_number',
        'country',
        'split_value',
        'split_type',
        'business_mobile',
    ];
}
