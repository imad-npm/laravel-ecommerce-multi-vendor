<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'order_id',
        'total_amount',
        'commission',
        'net_earnings',
        'is_paid',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}