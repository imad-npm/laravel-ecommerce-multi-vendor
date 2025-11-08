<?php
// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

        protected $fillable = [
        'user_id',
        'total',
        'status',
        'shipping_address_line_1',
        'shipping_city',
        'shipping_postal_code',
        'shipping_country',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    
}
