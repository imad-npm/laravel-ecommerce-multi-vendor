<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // ← add this

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // User.php
public function store()
{
    return $this->hasOne(Store::class,'user_id');
}
public function reviews()
{
    return $this->hasMany(Review::class);
}

public function hasPurchased(Product $product)
{
    return $this->orders()
        ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
        ->exists();
}

// app/Models/User.php

public function cart()
{
    return $this->hasOne(Cart::class);
}

public function orders()
{
    return $this->hasMany(Order::class);
}
}
