<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_gateway',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'payment_method_details',
        'error_message',
    ];

    protected $casts = [
        'payment_method_details' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
