<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description', 'logo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    public function averageRating()
    {
        // Get all reviews for all products in this store
        $productIds = $this->products()->pluck('id');
        $avg = \App\Models\Review::whereIn('product_id', $productIds)->avg('stars');
        return round($avg, 1) ?? 0;
    }
}
