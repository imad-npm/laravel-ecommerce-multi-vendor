<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];

    // App\Models\Product.php

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function getRatingAttribute()
    {
        return round($this->reviews()->avg('stars'), 1) ?? 0;
    }

    public function getSoldCountAttribute()
    {
        return $this->attributes['sold_count'] ?? 25;
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
