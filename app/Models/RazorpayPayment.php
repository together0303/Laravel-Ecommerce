<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazorpayPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'currency_rate' => 'integer',
        'status' => 'integer',
    ];
}
