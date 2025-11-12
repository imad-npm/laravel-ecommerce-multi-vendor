<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'amount',
        'status',
        'payment_method',
        'payment_details',
        'transaction_id',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function vendorEarnings()
    {
        return $this->hasMany(VendorEarning::class);
    }
}