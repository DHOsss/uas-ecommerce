<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['code', 'discount', 'min_purchase', 'expired_at', 'is_active'];

    protected $casts = [
        'is_active'  => 'boolean',
        'expired_at' => 'date',
    ];
}
